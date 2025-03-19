<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Compare Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            border-collapse: collapse;
        }

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .compare-table {
            width: 100%;
            border-collapse: collapse;
        }

        .compare-table th,
        .compare-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        .compare-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            width: 15%;
        }

        .compare-table td {
            width: auto;
            vertical-align: top;
        }

        .detailSameBox {
            width: 95%;
            margin-left: -10px;
        }

        .borderBox {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .topBar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .progress {
            /* width: 100%; */
            height: 8px;
            background: #ddd;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: green;
            border-radius: 4px;
        }

        .yellowLight {
            background: #FFBE0A;
        }

        .green {
            background: #00AE3B;
        }

        .brown {
            background: #D47A0C;
        }

        .yellow {
            background: #FFA436;
        }

        /* floor plan */

        .item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            background: white;
        }

        .imgBox {
            height: 180px;
            overflow: hidden;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f8f8;
            border: 1px solid #ddd;
            margin-bottom: 8px;
        }


        .imgBox img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 5px;
        }

        .textSlider {
            width: 100%;
        }

        .imgText {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .imgText span {
            font-weight: bold;
            display: block;
        }

        .imgText h6 {
            margin: 0;
            font-size: 14px;
            color: #007bff;
        }

        .siteDetails {
            margin-bottom: 10px;
        }

        @media print {

            /* Ensure exactly 3 images per page */
            .floorPlanPage {
                display: block;
                /* Treat each chunk as a block element */
                page-break-before: always;
                /* Start a new page after every 3 images */
            }

            /* Ensure each image block is fully visible and doesn't split */
            .item {
                page-break-inside: avoid;
            }

            /* This helps ensure <td> doesn't break */
            td {
                display: block;
                width: 100%;
                page-break-before: avoid;
                page-break-inside: avoid;
            }

            /* Force new page after every 3 images */
            .floorPlanPage:nth-child(3n) {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Compare Report</div>

        <table class="compare-table">
            {{-- property --}}
            <tr>
                <th>Property</th>
                @foreach ($properties as $property)
                    <td style="background-color: #EEF6FF;">
                        <div>
                            <div style="display: table; width: 100%;">
                                <div style="display: table-cell; vertical-align: middle; padding-right: 10px;">

                                    @php
                                        $builderLogo = $property->builder->builder_logo
                                            ? storage_path("app/public/builder/logo/{$property->builder->builder_logo}")
                                            : public_path('images/no_image_available.jpg');
                                    @endphp
                                    <img src="{{ $builderLogo }}"
                                        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div style="display: table-cell; vertical-align: middle;">
                                    <div>
                                        <strong>{{ $property->project_name }}</strong>
                                        <p>By {{ $property->builder->builder_name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p><img src="{{ public_path('frontend/assest/images/Home.png') }}" alt="Home"
                                        style="width: 25px;vertical-align: middle;"> <span
                                        style="margin-bottom: 3px; display: inline-block;">{{ $property->custom_property_type }}</span>
                                </p>
                                <p><img src="{{ public_path('frontend/assest/images/Location.png') }}" alt="Home"
                                        style="width: 25px;vertical-align: middle;"> <span
                                        style="margin-bottom: 3px; display: inline-block;">{{ $property->location->location_name }},
                                        {{ $property->city->city_name }}</span></p>
                                <p><strong>Possession By:</strong>
                                    {{ getFormatedDate($property->possession_by, 'M, Y') }}</p>
                                <p><strong>Price :</strong>
                                    Rs. {{ formatPriceUnit($property->price_from, $property->price_from_unit) }}
                                    @if($property->price_from != $property->price_to || $property->price_from_unit != $property->price_to_unit)
                                        - Rs. {{ formatPriceUnit($property->price_to, $property->price_to_unit) }}
                                    @endif
                                </p>

                            </div>
                        </div>

                    </td>
                @endforeach
            </tr>

            {{-- exio suggest --}}
            <tr>
                <th>Exio Suggest</th>
                @foreach ($properties as $property)
                    <td>
                        <div class="detailSameBox">
                            <div class="borderBox">
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-cell; vertical-align: middle; padding-right: 10px;">
                                        <img src="{{ public_path('frontend/assest/images/x-btn.png') }}" alt="x-btn"
                                            style="display: table; width: 100px;">
                                    </div>
                                    <div style="display: table-cell; vertical-align: middle; text-align: right;">
                                        <span style="color: #00AE3B;">{{ $property->exio_suggest_percentage }}%</span>
                                    </div>
                                </div>
                                @foreach (['Amenities' => 'amenities_percentage', 'Project Plan' => 'project_plan_percentage', 'Locality' => 'locality_percentage', 'Return of Investment' => 'return_of_investment_percentage'] as $title => $field)
                                    <div class="progressBox">
                                        <div class="topBar" style="display: table; width: 100%;">
                                            <h5
                                                style="display: table-cell; vertical-align: middle; text-align: left; width: 50%;">
                                                {{ $title }}
                                            </h5>
                                            <span
                                                style="display: table-cell; vertical-align: middle; text-align: right; width: 50%;">
                                                {{ $property->$field }}%
                                            </span>
                                        </div>
                                        <div class="barBox">
                                            <div class="progress">
                                                {!! renderProgressBar($property->$field) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                @endforeach
            </tr>

            {{-- property description  --}}
            <tr>
                <th>Property Description</th>
                @foreach ($properties as $property)
                    <td>{!! $property->project_about !!}</td>
                @endforeach
            </tr>

            {{-- overview --}}
            <tr>
                <th>Overview</th>
                @foreach ($properties as $property)
                    <td>
                        <div style="background-color: #EEF6FF;border-radius: 5px; padding: 5px; margin-bottom: 3px;">
                            <p style="margin-bottom: 0; line-height: 0.5;">Total Floors<p>
                            <div style="margin-top: -10px; font-size: 15px; font-weight: 600;">
                                {{ $property->total_floors }} Floors</div>
                        </div>
                        <div style="background-color: #EEF6FF;border-radius: 5px; padding: 5px; margin-bottom: 3px;">
                            <p style="margin-bottom: 0; line-height: 0.5;">Total Tower<p>
                            <div style="margin-top: -10px; font-size: 15px; font-weight: 600;">
                                {{ $property->total_tower }} Tower</div>
                        </div>
                        <div style="background-color: #EEF6FF;border-radius: 5px; padding: 5px; margin-bottom: 3px;">
                            <p style="margin-bottom: 0; line-height: 0.5;">Age of Construction<p>
                            <div style="margin-top: -10px; font-size: 15px; font-weight: 600;">
                                {{ getAgeOfConstruction($property->age_of_construction) }}</div>
                        </div>
                        <div style="background-color: #EEF6FF;border-radius: 5px; padding: 5px; margin-bottom: 3px;">
                            <p style="margin-bottom: 0; line-height: 0.5;">Property Type<p>
                            <div style="margin-top: -10px; font-size: 15px; font-weight: 600;">
                                {{ getPropertyType($property->property_type) }}</div>
                        </div>
                        @foreach ($property->projectDetails as $projectDetail)
                            <div
                                style="background-color: #EEF6FF;border-radius: 5px; padding: 5px; margin-bottom: 3px;">
                                <p style="margin-bottom: 0;">{{ $projectDetail->name }}<p>
                                <div style="margin-top: -10px; font-size: 15px; font-weight: 600;">
                                    {{ $projectDetail->value }}</div>
                            </div>
                        @endforeach
                    </td>
                @endforeach
            </tr>

            {{-- floor plan --}}
            @php
                $maxPerPage = 3;
                $propertyFloorPlans = [];

                // Collect all floor plans per property
                foreach ($properties as $propertyIndex => $property) {
                    $propertyFloorPlans[$propertyIndex] = $property->floorPlans->toArray();
                }

                // Determine max number of pages based on the largest set
                $maxPages = max(array_map(fn($plans) => ceil(count($plans) / $maxPerPage), $propertyFloorPlans));
            @endphp

            @for ($page = 0; $page < $maxPages; $page++)
                <tr>
                    <th> @if($page == 0) Floor Plan @endif </th> <!-- Display "Floor Plan" only on the first row -->
                    @foreach ($properties as $propertyIndex => $property)
                        <td>
                            @php
                                // Get the specific 3 items for the current page
                                $floorPlans = array_slice($propertyFloorPlans[$propertyIndex], $page * $maxPerPage, $maxPerPage);
                            @endphp

                            @foreach ($floorPlans as $floorPlan)
                                <div class="item">
                                    <div class="imgBox">
                                        @php
                                            $imagePath = storage_path("app/public/floor_plan/2d_image/{$floorPlan['2d_image']}");
                                        @endphp
                                        @if (!empty($floorPlan['2d_image']) && file_exists($imagePath) && !is_dir($imagePath))
                                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($imagePath)) }}" alt="{{ $floorPlan['type'] }}">
                                        @else
                                            <img src="{{ public_path('images/no_image_available.jpg') }}" alt="No Image Available">
                                        @endif
                                    </div>
                                    <div class="textSlider" style="display: table; width: 100%;">
                                        <div class="imgText" style="display: table-cell; width: 50%;">
                                            <span>Carpet Area</span>
                                            <h6>{{ $floorPlan['carpet_area'] ?? 'N/A' }} sqft</h6>
                                        </div>
                                        <div class="imgText" style="display: table-cell; width: 50%;">
                                            <span>Type</span>
                                            <h6>{{ $floorPlan['type'] ?? 'N/A' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endfor

            <tr>
                <th>Amenities</th>
                @foreach ($properties as $property)
                    <td>
                        {{ count($property->amenitiesList) }} Amenities
                        <ul>
                            @foreach ($property->amenitiesList as $amenity)
                                <li>{{ $amenity->amenity_name }}</li>
                            @endforeach
                        </ul>
                    </td>
                @endforeach
            </tr>

            <tr>
                <th>Locality</th>
                @foreach ($properties as $property)
                    <td>
                        <ul>
                            @foreach ($property->localities as $locality)
                                <li>
                                    {{ $locality->locality->locality_name }}({{ $locality->time_to_reach }} min) -
                                    {{ $locality->distance }} {{ $locality->distance_unit }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                @endforeach
            </tr>
        </table>
    </div>

</body>

</html>
