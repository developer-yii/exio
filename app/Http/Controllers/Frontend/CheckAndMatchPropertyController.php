<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\City;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Request;

class CheckAndMatchPropertyController extends Controller
{
    public function checkAndMatchProperty()
    {
        $propertyTypes = collect(Project::$propertyType)
            ->only(['residential', 'commercial'])
            ->toArray();

        $sqftOptions = [
            '1000' => '< 1000 Sq ft',
            '2000' => '< 2000 Sq ft',
            '5000' => '< 5000 Sq ft',
            '10000' => '< 10000 Sq ft',
        ];

        $budgets = [
            '25L-50L' => '25L-50L',
            '50L-75L' => '50L-75L',
            '75L-1CR' => '75L-1CR',
            '1CR-1.5CR' => '1CR-1.5CR',
            '1.5CR-2CR' => '1.5CR-2CR',
            '2CR+' => '2CR+',
        ];

        $cities = City::isActive()->pluck('city_name', 'id');
        $areas = Location::isActive()->pluck('location_name', 'id');

        return view('frontend.check-and-match-property.index', compact('propertyTypes', 'sqftOptions', 'cities', 'areas', 'budgets'));
    }

    public function checkAndMatchPropertySubmit(Request $request)
    {
        dd($request->all());
        return response()->json(['status' => true, 'message' => 'Property checked and matched successfully']);
    }

    public function getAmenities(Request $request)
    {
        $amenities = Amenity::query();

        if ($request->has('amenity_type')) {
            $amenities->where('amenity_type', $request->amenity_type);
        }

        $amenities = $amenities->isActive()->get();

        return response()->json(['status' => true, 'data' => $amenities]);
    }

    public function checkAndMatchPropertyResult(Request $request)
    {
        $filters = $request->only(['property_type', 'sqft', 'location', 'amenities', 'budget']);
        $location = $filters['location'] ? array_filter(explode(',', $filters['location'])) : [];

        $displayValues = $this->buildDisplayValues($filters, $location);

        $projects = Project::query();

        if (isset($filters['property_type'])) {
            $projects->where(function ($query) use ($filters) {
                $query->where('property_type', $filters['property_type'])
                    ->orWhere('property_type', 'both');
            });
        }

        if (isset($filters['sqft'])) {
            $projects->where(function ($query) use ($filters) {
                $query->whereHas('floorPlans', function ($q) use ($filters) {
                    $q->where(function ($subQ) use ($filters) {
                        foreach (explode(',', $filters['sqft']) as $sqft) {
                            $subQ->orWhere('carpet_area', '<=', (int)$sqft);
                        }
                    });
                });
            });
        }

        if (!empty($location)) {
            $projects->when(isset($location[1]), function ($query) use ($location) {
                $query->where('city_id', $location[1]);
            })->when(isset($location[0]), function ($query) use ($location) {
                $query->where('location_id', $location[0]);
            });
        }

        if (isset($filters['amenities'])) {
            $amenityIds = explode(',', $filters['amenities']);
            $projects->where(function ($query) use ($amenityIds) {
                foreach ($amenityIds as $amenityId) {
                    $query->whereRaw("FIND_IN_SET(?, amenities)", [$amenityId]);
                }
            });
        }

        if (isset($filters['budget'])) {
            $this->applyBudgetFilter($projects, $filters['budget']);
        }

        $projects = $projects->get();

        $priceUnit = Project::$priceUnit;

        return view('frontend.check-and-match-property.result', array_merge(
            $displayValues,
            [
                'allReqDataString' => http_build_query($filters),
                'projects' => $projects,
                'priceUnit' => $priceUnit
            ]
        ));
    }

    private function buildDisplayValues(array $filters, array $location): array
    {
        return [
            'displayProperty' => Project::$propertyType[$filters['property_type'] ?? ''] ?? '',
            'displaySqft' => isset($filters['sqft']) ? collect(explode(',', $filters['sqft']))
                ->map(fn($size) => "< {$size} Sq ft")
                ->implode(', ') : '',
            'displayLocation' => $this->formatLocation($location),
            'displayAmenities' => isset($filters['amenities']) ?
                Amenity::whereIn('id', explode(',', $filters['amenities']))
                ->pluck('amenity_name')
                ->implode(', ') : '',
            'displayBudget' => isset($filters['budget']) ? '₹' . str_replace(',', ', ₹', $filters['budget']) : ''
        ];
    }

    private function formatLocation(array $location): string
    {
        if (count($location) < 1) {
            return '';
        }

        $area = isset($location[0]) ? Location::find($location[0]) : null;
        $city = isset($location[1]) ? City::find($location[1]) : null;

        if (!$area && !$city) {
            return '';
        }

        $locationParts = [];
        if ($area && $area->location_name) {
            $locationParts[] = $area->location_name;
        }
        if ($city && $city->city_name) {
            $locationParts[] = $city->city_name;
        }

        return implode(', ', $locationParts);
    }

    private function applyBudgetFilter($query, string $budget): void
    {
        $budgetRange = explode('-', $budget);

        if (count($budgetRange) === 1) {
            // Handle "2CR+" case
            $minPrice = (float) str_replace(['CR', 'L'], '', $budgetRange[0]);
            $minPriceInLacs = str_contains($budgetRange[0], 'CR') ? $minPrice * 100 : $minPrice;

            $this->applyMinimumBudgetFilter($query, $minPriceInLacs);
        } else {
            // Handle range case
            $this->applyBudgetRangeFilter($query, $budgetRange);
        }
    }

    private function applyMinimumBudgetFilter($query, float $minPriceInLacs): void
    {
        $query->where(function ($q) use ($minPriceInLacs) {
            // Convert price to lacs based on unit
            $q->where(function ($subQ) use ($minPriceInLacs) {
                $subQ->where('price_from_unit', 'lacs')
                    ->where('price_from', '>=', $minPriceInLacs);
            })->orWhere(function ($subQ) use ($minPriceInLacs) {
                $subQ->where('price_from_unit', 'crores')
                    ->where('price_from', '>=', $minPriceInLacs / 100);
            });
        });
    }

    private function applyBudgetRangeFilter($query, array $budgetRange): void
    {
        // Extract min and max values from range (e.g. "25L-50L")
        $minValue = (float) str_replace(['CR', 'L'], '', $budgetRange[0]);
        $maxValue = (float) str_replace(['CR', 'L'], '', $budgetRange[1]);

        // Convert to lacs if CR
        $minInLacs = str_contains($budgetRange[0], 'CR') ? $minValue * 100 : $minValue;
        $maxInLacs = str_contains($budgetRange[1], 'CR') ? $maxValue * 100 : $maxValue;

        $query->where(function ($q) use ($minInLacs, $maxInLacs) {
            // Handle price in lacs
            $q->where(function ($subQ) use ($minInLacs, $maxInLacs) {
                $subQ->where('price_from_unit', 'lacs')
                    ->where('price_from', '>=', $minInLacs)
                    ->where('price_from', '<=', $maxInLacs);
            })
                // Handle price in crores
                ->orWhere(function ($subQ) use ($minInLacs, $maxInLacs) {
                    $subQ->where('price_from_unit', 'crores')
                        ->where('price_from', '>=', $minInLacs / 100)
                        ->where('price_from', '<=', $maxInLacs / 100);
                });
        });
    }
}
