<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\City;
use App\Models\Location;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // $areas = Location::isActive()->pluck('location_name', 'id')->groupBy('city_id');

        $checkandmatch = Setting::where('setting_key', 'check_match_video')->first();

        return view('frontend.check-and-match-property.index', compact('propertyTypes', 'sqftOptions', 'cities', 'areas', 'budgets', 'checkandmatch'));
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
        $filters = $request->only(['property_type', 'sqft', 'city', 'location', 'amenities', 'budget']);

        $displayValues = $this->buildDisplayValues($filters);

        $projects = Project::with('projectImages', 'projectBadge', 'floorPlans', 'city', 'location')->isActive();

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

        if (!empty($filters['city']) || !empty($filters['location'])) {
            $projects->when(isset($filters['city']), function ($query) use ($filters) {
                $query->where('city_id', $filters['city']);
            })->when(isset($filters['location']), function ($query) use ($filters) {
                $query->where('location_id', $filters['location']);
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

    private function buildDisplayValues(array $filters): array
    {
        return [
            'displayProperty' => Project::$propertyType[$filters['property_type'] ?? ''] ?? '',
            'displaySqft' => isset($filters['sqft']) ? collect(explode(',', $filters['sqft']))
                ->map(fn($size) => "< {$size} Sq ft")
                ->implode(', ') : '',
            'displayLocation' => $this->formatLocation($filters),
            'displayAmenities' => isset($filters['amenities']) ?
                Amenity::whereIn('id', explode(',', $filters['amenities']))
                ->pluck('amenity_name')
                ->implode(', ') : '',
            'displayBudget' => isset($filters['budget']) ? '₹' . str_replace(',', ', ₹', $filters['budget']) : ''
        ];
    }

    private function formatLocation(array $filters): string
    {
        if (empty($filters['city']) && empty($filters['location'])) {
            return '';
        }

        $area = isset($filters['location']) ? Location::find($filters['location']) : null;
        $city = isset($filters['city']) ? City::find($filters['city']) : null;

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
