<x-layouts.dashboard title="Application Settings">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />

            @can('setting.store')
                @if (!$isMaintenanceMode)
                    <button class="btn btn-primary enable-maintenance-mode" type="button" data-bs-toggle="modal"
                        data-bs-target="#confirmationModal">
                        Enable Maintenance Mode
                    </button>
                @else
                    <button class="btn btn-danger disable-maintenance-mode" type="button">
                        Disable Maintenance Mode
                    </button>
                @endif
            @endcan
        </div>

        <p class="mb-4">
            This page allows you to configure the application settings.<br />
            You can enable or disable maintenance mode, which will restrict access to the application while updates are
            being made.
        </p>

        <ul class="nav nav-pills mb-3 justify-content-center" role="tablist" id="settings-tabs">
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" id="application-tab"
                    data-bs-target="#application-settings" aria-controls="application-settings" aria-selected="true">
                    <i class="bx bx-cog me-1"></i> Application
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" id="seo-tab"
                    data-bs-target="#seo-settings" aria-controls="seo-settings" aria-selected="false">
                    <i class="bx bx-search me-1"></i> SEO
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" id="sidebar-tab"
                    data-bs-target="#sidebar-settings" aria-controls="sidebar-settings" aria-selected="false">
                    <i class="bx bx-layout me-1"></i> Sidebar
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" id="footer-tab"
                    data-bs-target="#footer-settings" aria-controls="footer-settings" aria-selected="false">
                    <i class="bx bx-underline me-1"></i> Footer
                </button>
            </li>
        </ul>

        <div class="tab-content mb-4">
            <div class="tab-pane fade show" id="application-settings" role="tabpanel" aria-labelledby="application-tab">
                <div class="card" id="application-settings">
                    <form class="card-body"
                        action="{{ route('dashboard.settings.application.update', $types['app'] ?? '') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h6>Application Settings</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="app_name">Name</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                        id="app_name" name="app_name"
                                        value="{{ old('app_name', $settings['app_name'] ?? '') }}" />
                                </div>
                                <x-input.error for="app_name" />
                            </div>
                            <div class="col-md-6" id="form-uploader-app_timezone">
                                <label class="form-label" for="app_timezone">Timezone</label>
                                <div class="input-group input-group-merge">
                                    <select class="form-select select2 @error('app_timezone') is-invalid @enderror"
                                        id="app_timezone" name="app_timezone"
                                        data-selected="{{ $settings['app_timezone'] ?? '' }}">
                                        @foreach ($timezones as $value => $label)
                                            <option value="{{ $label }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input.error for="app_timezone" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="app_description">Description</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control @error('app_description') is-invalid @enderror" id="app_description"
                                        name="app_description">{{ old('app_description', $settings['app_description'] ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="app_logo">Logo</label>
                                <div class="mt-2">
                                    <img id="logo-preview" src="{{ $settings['app_logo'] ?? '' }}" alt="Logo Preview"
                                        loading="lazy"
                                        style="width: 160px; height: 80px; object-fit: contain; display: {{ empty($settings['app_logo']) ? 'none' : 'inline-block' }};">
                                </div>
                                <div class="input-group input-group-merge mt-3">
                                    <input type="file" class="form-control @error('app_logo') is-invalid @enderror"
                                        id="app_logo" name="app_logo" accept="image/*" />
                                </div>
                                <x-input.error for="app_logo" />
                                <div class="form-text">
                                    Recommended resolution: <strong>160x80px</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="app_favicon">Favicon</label>
                                <div class="mt-2">
                                    <img id="favicon-preview" src="{{ $settings['app_favicon'] ?? '' }}"
                                        alt="Favicon Preview" loading="lazy"
                                        style="width: 160px; height: 80px; object-fit: contain; display: {{ empty($settings['app_favicon']) ? 'none' : 'inline-block' }};">
                                </div>
                                <div class="input-group input-group-merge mt-3">
                                    <input type="file"
                                        class="form-control @error('app_favicon') is-invalid @enderror"
                                        id="app_favicon" name="app_favicon" accept="image/*" />
                                </div>
                                <x-input.error for="app_favicon" />
                                <div class="form-text">
                                    Recommended resolution: <strong>40x40px</strong>
                                </div>
                            </div>
                        </div>

                        @can('setting.update')
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Settings</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        @else
                            <div class="alert alert-warning mt-4">
                                You do not have permission to update uploader settings.
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="seo-settings" role="tabpanel" aria-labelledby="seo-tab">
                <div class="card mb-4" id="seo-settings">
                    <form class="card-body"
                        action="{{ route('dashboard.settings.application.update', $types['seo'] ?? '') }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <h6>SEO Settings</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="seo_title">Title</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"
                                        class="form-control @error('seo_title') is-invalid @enderror" id="seo_title"
                                        name="seo_title"
                                        value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" />
                                </div>
                                <x-input.error for="seo_title" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="seo_author">Author</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"
                                        class="form-control @error('seo_author') is-invalid @enderror" id="seo_author"
                                        name="seo_author"
                                        value="{{ old('seo_author', $settings['seo_author'] ?? '') }}" />
                                </div>
                                <x-input.error for="seo_author" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="seo_image_width">Image Width</label>
                                <div class="input-group input-group-merge">
                                    <input type="number"
                                        class="form-control @error('seo_image_width') is-invalid @enderror"
                                        id="seo_image_width" name="seo_image_width"
                                        value="{{ old('seo_image_width', $settings['seo_image_width'] ?? '') }}" />
                                </div>
                                <x-input.error for="seo_image_width" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="seo_image_height">Image Height</label>
                                <div class="input-group input-group-merge">
                                    <input type="number"
                                        class="form-control @error('seo_image_height') is-invalid @enderror"
                                        id="seo_image_height" name="seo_image_height"
                                        value="{{ old('seo_image_height', $settings['seo_image_height'] ?? '') }}" />
                                </div>
                                <x-input.error for="seo_image_height" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="seo_description">Description</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control @error('seo_description') is-invalid @enderror" id="seo_description"
                                        name="seo_description">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                                </div>
                                <x-input.error for="seo_description" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="seo_keywords">Keywords</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control @error('seo_keywords') is-invalid @enderror" id="seo_keywords" name="seo_keywords">{{ old('seo_keywords', $settings['seo_keywords'] ?? '') }}</textarea>
                                </div>
                                <x-input.error for="seo_keywords" />
                            </div>
                        </div>

                        @can('setting.update')
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Settings</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        @else
                            <div class="alert alert-warning mt-4">
                                You do not have permission to update uploader settings.
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="sidebar-settings" role="tabpanel" aria-labelledby="sidebar-tab">
                <div class="card mb-4" id="sidebar-settings">
                    <form class="card-body"
                        action="{{ route('dashboard.settings.application.update', $types['sidebar'] ?? '') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h6>Sidebar Settings</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="sidebar_name">Name</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"
                                        class="form-control @error('sidebar_name') is-invalid @enderror"
                                        id="sidebar_name" name="sidebar_name"
                                        value="{{ old('sidebar_name', $settings['sidebar_name'] ?? '') }}" />
                                </div>
                                <x-input.error for="sidebar_name" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="sidebar_name_size">Name Size</label>
                                <div class="input-group input-group-merge">
                                    <input type="number" min="0.5" max="2" step="0.01"
                                        placeholder="0.5"
                                        class="form-control @error('sidebar_name_size') is-invalid @enderror"
                                        id="sidebar_name_size" name="sidebar_name_size"
                                        value="{{ old('sidebar_name_size', $settings['sidebar_name_size'] ?? '') }}" />
                                </div>
                                <x-input.error for="sidebar_name_size" />
                                <div class="form-text">
                                    Note: value use rem unit, e.g. 1.25 means 1.25rem. This will affect the sidebar name
                                    font size.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="sidebar_logo">Logo</label>
                                <div class="mt-2">
                                    <img id="sidebar-logo-preview" src="{{ $settings['sidebar_logo'] ?? '' }}"
                                        alt="Logo Preview" loading="lazy"
                                        style="width: 160px; height: 80px; object-fit: contain; display: {{ empty($settings['sidebar_logo']) ? 'none' : 'inline-block' }};">
                                </div>
                                <div class="input-group input-group-merge mt-3">
                                    <input type="file"
                                        class="form-control @error('sidebar_logo') is-invalid @enderror"
                                        id="sidebar_logo" name="sidebar_logo" accept="image/*" />
                                </div>
                                <x-input.error for="sidebar_logo" />
                                <div class="form-text">
                                    Recommended resolution: <strong>80x80px</strong>
                                </div>
                            </div>
                        </div>

                        @can('setting.update')
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Settings</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        @else
                            <div class="alert alert-warning mt-4">
                                You do not have permission to update uploader settings.
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="footer-settings" role="tabpanel" aria-labelledby="footer-tab">
                <div class="card mb-4" id="footer-settings">
                    <form class="card-body form-repeater"
                        action="{{ route('dashboard.settings.application.update', $types['footer'] ?? '') }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <h6>Footer Settings</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="footer_copyright">Copyright</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"
                                        class="form-control @error('footer_copyright') is-invalid @enderror"
                                        id="footer_copyright" name="footer_copyright"
                                        value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '') }}" />
                                </div>
                                <x-input.error for="footer_copyright" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="footer_copyright_url">Copyright URL</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"
                                        class="form-control @error('footer_copyright_url') is-invalid @enderror"
                                        id="footer_copyright_url" name="footer_copyright_url"
                                        value="{{ old('footer_copyright_url', $settings['footer_copyright_url'] ?? '') }}" />
                                </div>
                                <x-input.error for="footer_copyright_url" />
                            </div>
                        </div>

                        <div data-repeater-list="link">
                            @foreach ($settings as $key => $value)
                                @if (str_starts_with($key, 'footer_') && strpos($key, 'copyright') === false && str_ends_with($key, '_url'))
                                    <div class="row g-3 align-items-end mb-2" data-repeater-item>
                                        <input type="hidden" name="link[{{ $loop->index }}][key]" value="{{ Str::between($key, 'footer_', '_url') }}" id="link_key_{{ $loop->index }}" />
                                        <div class="col-md-5">
                                            <label class="form-label">Link Title</label>
                                            <input type="text"
                                                class="form-control @error("link[{{ $loop->index }}][title]") is-invalid @enderror"
                                                name="link[{{ $loop->index }}][title]" value="{{ $settings[str_replace('_url', '_title', $key)] ?? '' }}" />
                                            <x-input.error for="link[{{ $loop->index }}][title]" />
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Link URL</label>
                                            <input type="text"
                                                class="form-control @error("link[{{ $loop->index }}][url]") is-invalid @enderror"
                                                name="link[{{ $loop->index }}][url]" value="{{ $value }}" />
                                            <x-input.error for="link[{{ $loop->index }}][url]" />
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger w-100" data-repeater-delete>
                                                <i class="bx bx-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="pt-4">
                            <button type="button" class="btn btn-primary me-sm-3 me-1" data-repeater-create>
                                <i class="bx bx-plus me-1"></i>
                                <span class="align-middle">Add</span>
                            </button>
                            @can('setting.update')
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Settings</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @can('setting.store')
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="role-title">
                                    Enable Maintenance Mode
                                </h3>
                            </div>
                            <form id="enableMaintenanceModeForm" class="row g-3" method="POST"
                                action="{{ route('dashboard.settings.application.store') }}">
                                @csrf
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="secret">Secret Key</label>
                                    <input type="text" id="secret" name="secret" class="form-control"
                                        placeholder="Enter a secret key" required />
                                    <div class="form-text">
                                        This key will be used to access the application while it is in maintenance mode.
                                        <br />
                                        Make sure to keep it secure and share it only with authorized personnel.
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1"
                                        id="add-new-record-submit-btn">Submit</button>
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <form id="disableMaintenanceModeForm" action="{{ route('dashboard.settings.application.store') }}"
                method="POST" class="d-none">
                @csrf
            </form>
        @endcan
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/jquery-repeater/jquery-repeater.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                var rowCount = 0;

                @foreach ($settings as $key => $value)
                    @if (str_starts_with($key, 'footer_') && strpos($key, 'copyright') === false && str_ends_with($key, '_url'))
                        rowCount++;
                    @endif
                @endforeach

                const formRepeater = $('.form-repeater');
                if (formRepeater.length) {
                    formRepeater.repeater({
                        show: function() {
                            $(this).find('input[name^="link"]').each(function() {
                                var name = $(this).attr('name');
                                name = name.replace(/\[(\d+)\]/, function(_, index) {
                                    return '[' + rowCount + ']';
                                });
                                $(this).attr('name', name);
                            });

                            rowCount++;

                            $(this).slideDown();
                        },
                        hide: function() {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'This action cannot be undone. Do you really want to remove this item?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, remove it!',
                                cancelButtonText: 'No, keep it',
                                customClass: {
                                    confirmButton: 'btn btn-label-danger',
                                    cancelButton: 'btn btn-primary'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const fieldKey = $(this).find('input[name^="link"]').attr('value');
                                    $(this).slideUp();

                                    if (!fieldKey || fieldKey === '') return;

                                    $(this).remove();

                                    $.ajax({
                                        url: "{{ route('dashboard.settings.application.destroy', ':id') }}".replace(':id', fieldKey),
                                        type: 'DELETE',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            Swal.fire({
                                                title: 'Deleted!',
                                                text: response.message,
                                                icon: 'success'
                                            });
                                        },
                                        error: function(xhr) {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: xhr.responseJSON.message,
                                                icon: 'error'
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                $('#settings-tabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    const tabId = $(e.target).attr('id');
                    let tab = '';
                    switch (tabId) {
                        case 'application-tab':
                            tab = 'application';
                            break;
                        case 'seo-tab':
                            tab = 'seo';
                            break;
                        case 'sidebar-tab':
                            tab = 'sidebar';
                            break;
                        case 'footer-tab':
                            tab = 'footer';
                            break;
                    }
                    if (tab) {
                        const url = new URL(window.location);
                        url.searchParams.set('tab', tab);
                        window.history.replaceState({}, '', url);
                        $('.tab-pane.active form').each(function() {
                            let $input = $(this).find('input[name="tab"]');
                            if ($input.length === 0) {
                                $input = $('<input type="hidden" name="tab" />').appendTo($(this));
                            }
                            $input.val(tab);
                        });
                    }
                });

                const params = new URLSearchParams(window.location.search);
                let tab = params.get('tab');
                if (!tab) {
                    tab = 'application';
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);
                }
                if (tab) {
                    $(`#${tab}-tab`).tab('show');
                    setTimeout(function() {
                        $(`#${tab}-settings form`).each(function() {
                            let $input = $(this).find('input[name="tab"]');
                            if ($input.length === 0) {
                                $input = $('<input type="hidden" name="tab" />').appendTo($(this));
                            }
                            $input.val(tab);
                        });
                    }, 100);
                }
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                autosize($('#app_description'));
                autosize($('#seo_description'));
                autosize($('#seo_keywords'));
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                const select2Elements = $('.select2');
                if (select2Elements.length) {
                    select2Elements.each(function() {
                        $(this).select2({
                            dropdownParent: $(this).closest('form'),
                            placeholder: 'Select an option',
                            allowClear: false,
                            width: '100%',
                        });
                    });
                }
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                $('.select2').each(function() {
                    const selectedValues = $(this).data('selected') || null;
                    if (selectedValues == null) return;

                    $(this).val(selectedValues).trigger('change');
                });
            });
        </script>
        @can('setting.update')
            <script type="text/javascript" @cspNonce>
                $(document).ready(function() {
                    function setupImagePreview(inputId, previewId) {
                        const input = document.getElementById(inputId);
                        const preview = document.getElementById(previewId);

                        if (!input || !preview) return;

                        input.addEventListener('change', function() {
                            const file = input.files && input.files[0];
                            if (file) {
                                preview.src = URL.createObjectURL(file);
                                preview.style.display = '';
                            } else {
                                preview.src = '';
                                preview.style.display = 'none';
                            }
                        });
                    }

                    setupImagePreview('app_logo', 'logo-preview');
                    setupImagePreview('app_favicon', 'favicon-preview');
                    setupImagePreview('sidebar_logo', 'sidebar-logo-preview');
                });
            </script>
        @else
            <script type="text/javascript" @cspNonce>
                $(function() {
                    $('form')
                        .not('#enableMaintenanceModeForm, #disableMaintenanceModeForm')
                        .find('input, select, textarea')
                        .prop('disabled', true);

                    $('form')
                        .not('#enableMaintenanceModeForm, #disableMaintenanceModeForm')
                        .find('button[type="submit"]')
                        .remove();
                });
            </script>
        @endcan
        @can('setting.store')
            <script type="text/javascript" @cspNonce>
                $('#enableMaintenanceModeForm').on('submit', function(e) {
                    e.preventDefault();
                    const secret = $('#secret').val();
                    if (secret.trim() === '') {
                        alert('Secret key is required.');
                        return;
                    }
                    this.submit();
                });

                $('.disable-maintenance-mode').on('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to disable maintenance mode. This will make the application accessible to all users.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, disable it!',
                        cancelButtonText: 'No, cancel!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#disableMaintenanceModeForm').submit();
                        }
                    });
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
