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

                    <div class="contactBtn">
                        <a class="linkBtn" href="{{ route('contact-us') }}">Contact Exio Agent</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>