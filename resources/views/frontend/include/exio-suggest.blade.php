<div class="col-lg-4">
    <div id="rightStickySection">
        <div class="StickyBox">
            <div class="topReportBtn">
                <a href="javascript:void(0)">
                    <i class="bi bi-file-earmark"></i> Download Insight Report
                </a>
            </div>
            <div class="insightReport">
                <div class="reportTitle">
                    <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                    <span>{{ $project->exio_suggest_percentage }}%</span>
                </div>
                <div class="ourReportdetail">
                    @foreach(['Amenities' => 'amenities_percentage', 'Project Plan' => 'project_plan_percentage', 'Locality' => 'locality_percentage', 'Return of Investment' => 'return_of_investment_percentage'] as $title => $field)
                    <div class="boxOne comBoxPersantage">
                        <div class="topBar">
                            <h5>{{ $title }}</h5>
                            <span>{{ $project->$field }}%</span>
                        </div>
                        <div class="barBox">
                            <div class="progress">
                                {!! renderProgressBar($project->$field) !!}
                            </div>
                            <p>It is a long established fact that a reader will be distracted by the
                                readable content of a page when looking at its layout.</p>
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