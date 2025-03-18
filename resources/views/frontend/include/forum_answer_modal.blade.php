<div class="modal fade askModal" id="answerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="answerForm">
                @csrf
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5>Add Answer</h5>
                    <input type="hidden" name='forum-id' id='forum-id' class="forum-id">
                    <div class="enterQue form-group mb-1">
                        <textarea name="answer" id="answer" placeholder="Add your answer here" rows="5" cols="50"></textarea>
                        <p style="margin-bottom: 0px !important;">Characters remaining: <span id="answerCharCount">1000/1000</span></p>
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <div class="g-recaptcha-response" id="ans-recaptcha"></div>
                        <span class="error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>