<div class="offcanvas-footer border-top">
    @if ($type === 'show')
        <div class="d-flex flex-column ms-3">
            <span class="text-muted mb-1 mt-1">Created at: <span id="show-created-at"></span></span>
            <span class="text-muted mb-1">Last updated: <span id="show-last-updated"></span></span>
        </div>
    @else
        <div class="d-flex ms-3 mb-3 mt-3">
            <button type="submit" id="{{ $isCreate ? 'add-new' : 'edit' }}-record-submit-btn"
                class="btn btn-primary data-submit me-sm-3 me-1">
                {{ $isCreate ? 'Create Record' : 'Save Changes' }}
            </button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">
                Cancel
            </button>
        </div>
    @endif
</div>
