<div id="tosLinksValidation" class="content">
    <div class="content-header mb-3">
        <h3 class="mb-1">Agreement</h3>
        <span>Term of Service</span>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="privacy_policy" name="privacy_policy" required
                    tabindex="0">
                <label class="form-check-label" for="privacy_policy">
                    I agree to the
                    <a href="{{ route('privacy.policy') }}" target="_blank"
                        class="link-primary text-decoration-none">
                        Privacy Policy
                    </a>.
                </label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="agreement" name="agreement" required tabindex="0">
                <label class="form-check-label" for="agreement">
                    I agree to the
                    <a href="{{ route('tos.policy') }}" target="_blank" class="link-primary text-decoration-none">
                        Terms of Service
                    </a>.
                </label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" tabindex="0">
                <label class="form-check-label" for="newsletter">
                    I agree to receive the newsletter.
                </label>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-between">
            <button class="btn btn-primary btn-prev">
                <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                <span class="align-middle d-sm-inline-block d-none">Previous</span>
            </button>
            <button type="submit" class="btn btn-success btn-next btn-submit">Submit</button>
        </div>
    </div>
</div>
