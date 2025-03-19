<div class="modal fade propertyModal" id="propertyModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="modalgallery">
                            <div class="top-img comImg">
                                <img src="" alt="" id="coverImage">
                            </div>
                            <div class="multyimg"></div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="modalTextBox">
                            <div class="priceAndshare">
                                <div class="price">
                                    <h5 id="property_price"></h5>
                                    <h5 id="property_name" class="two-line-text"></h5>
                                </div>
                                <ul>
                                    <li><a href="javascript:void(0)"><i
                                                class="fa-regular fa-heart heartIconFill"></i>Save</a></li>
                                    <li><a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#share_property" class="share_property"><i
                                                class="fa-solid fa-arrow-up-from-bracket"></i>Share</a></li>
                                    <li><button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button></li>
                                </ul>
                            </div>
                            <div class="locationProperty">
                                <div class="homeBox comBox">
                                    <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                                    <p id="custom_type" class="one-line-text"></p>
                                </div>
                                <div class="location comBox">
                                    <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                                    <p id="location" class="one-line-text"></p>
                                </div>
                            </div>

                            <div class="discriptBox">
                                <p><strong>Description:</strong><span id="description" class="five-line-text"></span></p>
                            </div>
                            <div class="overViewBox">
                                <div class="overBox">
                                    <span>Total Floors</span>
                                    <h6 id="total_floor" class="one-line-text"></h6>
                                </div>
                                <div class="overBox">
                                    <span>Total Tower</span>
                                    <h6 id="total_tower" class="one-line-text"></h6>
                                </div>
                                <div class="overBox">
                                    <span>Age of Construction</span>
                                    <h6 id="age_of_construction"></h6>
                                </div>
                                <div class="overBox">
                                    <span>Property Type</span>
                                    <h6 id="property_type"></h6>
                                </div>
                            </div>
                            <div class="btn-container">
                                <a class="btn btnWp" id="whatsapplink" target="_blank"><img
                                        src="{{ $baseUrl }}assest/images/wpicon.png" alt="wpicon">Quick Connect</a>
                                <a href="" class="btn linkBtn" id="more-details">More Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>