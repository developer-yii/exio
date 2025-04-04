<div class="col-lg-4">
    <div id="rightStickySection">
        <div class="StickyBox">
            @if($project->insights_report_file)
                <div class="topReportBtn">
                    <a href="javascript:void(0)" class="downloadInsightReportPdf" data-id="{{ $project->id }}">
                        <i class="bi bi-file-earmark"></i> Download Insight Report
                    </a>
                </div>
            @endif
            <div class="insightReport">
                <div class="reportTitle">
                    <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                    <span>{{ $project->exio_suggest_percentage }}%</span>
                </div>
                <div class="ourReportdetail">                    
                    @foreach([
                        'section-a' => 'amenities_percentage',
                        'section-b' => 'project_plan_percentage', 
                        'section-c' => 'locality_percentage', 
                        'section-d' => 'return_of_investment_percentage'
                    ] as $key => $field)
                    <div class="boxOne comBoxPersantage">
                        <div class="topBar">
                            <h5>{{ $sections[$key]->setting_value ?? '' }}</h5>
                            <span>{{ $project->$field }}%</span>
                        </div>
                        <div class="barBox">
                            <div class="progress">
                                {!! renderProgressBar($project->$field) !!}
                            </div>
                            <p>{{ $sections[$key]->description ?? 'No description available' }}</p>
                        </div>
                    </div>
                    @endforeach

                    @php
                        use App\Models\Setting;
                        $whatsappNo = Setting::where('setting_key','support_mobile')->value('setting_value');
                        $projectName = $project->project_name;
                        $priceRange = formatPriceRange($project->price_from, $project->price_from_unit, $project->price_to, $project->price_to_unit);
                        $propertyType = getPropertyType($project->property_type);
                        $whatsappMessage = urlencode("Check out this property!\n\n*Project Name:* $projectName\n*Price:* $priceRange\n*Property Type:* $propertyType\n" . url()->current());
                    @endphp
                    <div class="contactBtn">
                        
                        <a class="linkBtn" href="https://wa.me/{{ $whatsappNo }}?text={{ $whatsappMessage }}" target="_blank">
    Contact Exio Agent
</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>