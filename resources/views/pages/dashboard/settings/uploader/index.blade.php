<x-layouts.dashboard title="Uploader Settings">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
        </div>

        <div class="card border-info shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-3">
                    This page allows you to configure the uploader settings for your application.<br />
                    You can manage file upload limits, allowed file types, and other related settings.
                </p>
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-1 fw-semibold">Uploader Settings Information</h6>
                        <ul class="mb-0 ps-3 small text-muted">
                            <li>
                                <strong>Allowed file types: </strong>
                                <span class="text-dark">{{ implode(', ', array_values($fileTypes)) }}</span>
                            </li>
                            <li>
                                <strong>Maximum file size per upload: </strong>
                                <span class="text-dark">{{ $serverThreshold }} Kilobytes (KB)</span>
                            </li>
                            <li>
                                <strong>Please select the allowed mime types for each file type as needed.</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <form id="form-uploader-settings" class="card-body"
                action="{{ route('dashboard.settings.uploader.store') }}" method="POST">
                @csrf

                @foreach ($fileUploaderConfigs as $index => $config)
                    <h6>{{ ucwords(str_replace('_', ' ', $index)) }} Uploader Settings</h6>
                    <div class="row g-3">
                        <div class="col-md-3" id="form-uploader-{{ $loop->index }}-type">
                            <label class="form-label" for="multicol-{{ $index }}-type">
                                <i class="bx bx-file"></i>
                                File Type
                            </label>
                            <div class="input-group input-group-merge">
                                <select class="form-select select2 @error($index . '-type') is-invalid @enderror"
                                    id="{{ $index }}-type" name="{{ $index }}-type"
                                    data-selected="{{ $config['type'] ?? '' }}">
                                    @foreach ($fileTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="form-uploader-{{ $loop->index }}-max-size">
                            <label class="form-label" for="multicol-{{ $index }}-max-size">
                                <i class="bx bx-expand"></i>
                                Max Size
                            </label>
                            <div class="input-group input-group-merge">
                                <select class="form-select select2 @error($index . '-max_size') is-invalid @enderror"
                                    id="{{ $index }}-max_size" name="{{ $index }}-max_size"
                                    data-selected="{{ $config['max_size'] ?? '' }}">
                                    @foreach ($maxSizeOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="form-uploader-{{ $loop->index }}-mimes">
                            <label class="form-label" for="multicol-{{ $index }}-mimes">
                                <i class="bx bx-list-check"></i>
                                Mime Type
                            </label>
                            <div class="input-group input-group-merge">
                                <select class="form-select select2 @error($index . '-mimes') is-invalid @enderror"
                                    id="{{ $index }}-mimes" name="{{ $index }}-mimes[]"
                                    data-selected="{{ $config['mimes'] ?? '' }}" multiple>
                                    @if (isset($config['type']) && $config['type'] === 'image')
                                        @foreach ($imageExtensions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($mimesOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    @if (!$loop->last)
                        <hr class="my-4 mx-n4" />
                    @endif
                @endforeach

                @can('setting.uploader.store')
                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary">Clear</button>
                    </div>
                @else
                    <div class="alert alert-warning mt-4">
                        You do not have permission to update uploader settings.
                    </div>
                @endcan
            </form>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                $('.select2').each(function() {
                    const $select = $(this);
                    const parentDiv = $select.closest('div[id^="form-uploader-"]');
                    $select.select2({
                        dropdownParent: parentDiv,
                        placeholder: 'Select an option',
                        allowClear: false,
                        width: '100%',
                        closeOnSelect: !$select.prop('multiple'),
                    });

                    const selected = $select.data('selected');
                    if (selected !== undefined && selected !== null && selected !== '') {
                        if ($select.prop('multiple')) {
                            $select.val(String(selected).split(',')).trigger('change');
                        } else {
                            $select.val(String(selected)).trigger('change');
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                $('form').on('reset', function() {
                    $(this).find('.select2').each(function() {
                        const $select = $(this);
                        $select.val(null).trigger('change');
                    });
                });
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                const mimesOptions = @json($mimesOptions ?? []);
                const imageExtensions = @json($imageExtensions ?? []);

                $('#form-uploader-settings').on('change', 'select[id$="-type"]', function() {
                    const $typeSelect = $(this);
                    const selectedType = $typeSelect.val();
                    const $mimesSelect = $typeSelect.closest('.row').find('select[id$="-mimes"]');

                    let options = [];
                    let prevSelected = $mimesSelect.val() || [];
                    prevSelected = Array.isArray(prevSelected) ? prevSelected : [prevSelected];

                    $mimesSelect.empty();

                    if (selectedType === 'image') {
                        $.each(imageExtensions, function(value, label) {
                            options.push(new Option(label, value));
                        });
                    } else {
                        $.each(mimesOptions, function(value, label) {
                            options.push(new Option(label, value));
                        });
                    }

                    $mimesSelect.append(options);

                    const allowedValues = options.map(opt => String(opt.value));
                    const filteredSelected = prevSelected.filter(val => allowedValues.includes(String(val)));

                    $mimesSelect.val(filteredSelected).trigger('change.select2');
                });
            });
        </script>
        @can('setting.uploader.store')
            <script type="text/javascript" @cspNonce>
                $(document).ready(function() {
                    $('#form-uploader-settings').on('submit', function(e) {
                        e.preventDefault();
                        const form = $(this);
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'You are about to update the uploader settings. This action will affect file uploads.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, update it!',
                            cancelButtonText: 'No, cancel!',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.off('submit').submit();
                            }
                        });
                    });
                });
            </script>
        @else
            <script type="text/javascript" @cspNonce>
                $(document).ready(function() {
                    $('form')
                        .find('input, select, textarea')
                        .prop('disabled', true)
                        .end()
                        .find('button[type="submit"]')
                        .remove();
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
