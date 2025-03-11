<div class="modal fade share_property" id="share_property" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="titlebox">
                    <p>Share Property</p>
                </div>
                <div class="iconBox">
                    <ul>
                        <li>
                            <a href="javascript:void(0)" id="whatsapp-link" class="social_media_share"><i
                                    class="fa-brands fa-whatsapp"></i></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="facebook-link" class="social_media_share"><i
                                    class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="twitter-link" class="social_media_share"><i
                                    class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="linkedin-link" class="social_media_share"><i
                                    class="fa-brands fa-linkedin"></i></a>
                        </li>
                        {{-- <li>
                            <a href="javascript:void(0)" id="email-link" class="social_media_share"><i
                                    class="fa-brands fa-email"></i></a>
                        </li> --}}
                    </ul>
                </div>
                <div class="input-group">
                    <input type="text" id="copy-link" class="form-control" aria-describedby="basic-addon2" disabled>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button"
                            onClick="copyToClipboard()">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>