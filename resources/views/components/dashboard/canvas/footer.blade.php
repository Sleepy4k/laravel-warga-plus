<div class="offcanvas-footer border-top">
    @if ($type === 'show')
        <div class="d-flex flex-column ms-3">
            <span class="text-muted mb-1 mt-1">Created at: <span id="show-created-at"></span></span>
            <span class="text-muted mb-1">Last updated: <span id="show-last-updated"></span></span>
        </div>
    @else
        <div class="d-flex ms-3 mb-3 mt-3">
            @if ($type === 'create')
                <button type="submit" id="add-new-record-submit-btn"
                    class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            @else
                <button type="submit" id="edit-record-submit-btn"
                    class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            @endif
        </div>
    @endif
</div>
