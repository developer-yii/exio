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
        // Validate and sanitize input data
        $propertyType = $request->input('property_type');
        $sqft = $request->input('sqft');
        $location = $request->input('location') ? array_filter(explode(',', $request->location)) : [];
        $amenities = $request->input('amenities');
        $budget = $request->input('budget');

        // Format display values with null coalescing operator
        $displayProperty = Project::$propertyType[$propertyType] ?? '';

        $displaySqft = $sqft ? collect(explode(',', $sqft))
            ->map(fn($size) => "< {$size} Sq ft")
            ->implode(', ') : '';

        $displayLocation = '';
        if (count($location) === 2) {
            $area = Location::find($location[0]);
            $city = City::find($location[1]);
            if ($area && $city) {
                $displayLocation = "{$area->location_name}, {$city->city_name}";
            }
        }

        $displayAmenities = $amenities ? Amenity::whereIn('id', explode(',', $amenities))
            ->pluck('amenity_name')
            ->implode(', ') : '';

        $displayBudget = $budget ? '₹' . str_replace(',', ', ₹', $budget) : '';

        $allReqDataString = http_build_query($request->only('property_type', 'sqft', 'location', 'amenities', 'budget'));

        $projects = Project::query();

        if ($request->has('property_type')) {
            $projects->where('property_type', $request->property_type)
                ->orWhere('property_type', 'both');
        }

        // if ($request->has('sqft')) {
        //     $projects->where('sqft', $request->sqft);
        // }

        if ($request->has('location')) {
            if (isset($location[1])) {
                $projects->where('city_id', $location[1]);
            }
            if (isset($location[0])) {
                $projects->where('location_id', $location[0]);
            }
        }

        // if ($request->has('amenities')) {
        //     $selectedAmenityIds = explode(',', $request->amenities);

        //     $projects->where(function ($query) use ($selectedAmenityIds) {
        //         $query->where(function ($q) use ($selectedAmenityIds) {
        //             foreach ($selectedAmenityIds as $amenityId) {
        //                 // Convert stored amenities string to array and check if it contains the amenity ID
        //                 $q->whereRaw('FIND_IN_SET(?, amenities)', [$amenityId]);
        //             }
        //         });
        //     });
        // }

        if ($request->has('budget')) {
            $budgetRange = explode('-', $request->budget);

            // Handle special case for "2CR+"
            if (count($budgetRange) === 1) {
                // For "2CR+" case, only check minimum price
                $minPrice = (float) str_replace(['CR', 'L'], '', $budgetRange[0]);
                $minPriceInLacs = str_contains($budgetRange[0], 'CR') ? $minPrice * 100 : $minPrice;

                $projects->where(function ($query) use ($minPriceInLacs) {
                    $query->where(function ($q) use ($minPriceInLacs) {
                        $q->where('price_from_unit', 'lacs')
                            ->where('price_from', '>=', $minPriceInLacs);
                    })->orWhere(function ($q) use ($minPriceInLacs) {
                        $q->where('price_from_unit', 'crores')
                            ->where('price_from', '>=', $minPriceInLacs / 100);
                    });
                });
            } else {
                // Normal range case (e.g. "25L-50L" or "75L-1CR")
                $minPrice = (float) str_replace(['CR', 'L'], '', $budgetRange[0]);
                $maxPrice = (float) str_replace(['CR', 'L'], '', $budgetRange[1]);

                // Convert everything to lacs for comparison
                $minPriceInLacs = str_contains($budgetRange[0], 'CR') ? $minPrice * 100 : $minPrice;
                $maxPriceInLacs = str_contains($budgetRange[1], 'CR') ? $maxPrice * 100 : $maxPrice;

                $projects->where(function ($query) use ($minPriceInLacs, $maxPriceInLacs) {
                    // Check price_from is within range when in lacs
                    $query->where(function ($q) use ($minPriceInLacs, $maxPriceInLacs) {
                        $q->where('price_from_unit', 'lacs')
                            ->where('price_from', '>=', $minPriceInLacs)
                            ->where('price_from', '<=', $maxPriceInLacs);
                    })
                        // Check price_from is within range when in crores
                        ->orWhere(function ($q) use ($minPriceInLacs, $maxPriceInLacs) {
                            $q->where('price_from_unit', 'crores')
                                ->where('price_from', '>=', $minPriceInLacs / 100)
                                ->where('price_from', '<=', $maxPriceInLacs / 100);
                        });
                });
            }
        }

        $projects = $projects->get();

        dd($projects);

        return view('frontend.check-and-match-property.result', compact(
            'displayProperty',
            'displaySqft',
            'displayLocation',
            'displayAmenities',
            'displayBudget',
            'allReqDataString',
            'projects'
        ));
    }
}
