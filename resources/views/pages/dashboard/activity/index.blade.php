<x-layouts.dashboard title="Activity">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/lightbox/css/lightbox.min.css') }}" @cspNonce />
        <style @cspNonce>
            .activity-image {
                max-width: 100px;
                max-height: 100px;
                border-radius: 10%;
                object-fit: cover;
            }
        </style>
        @canany(['activity.create', 'activity.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('activity.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Activity
                </button>
            @endcan
        </div>

        <div class="row" id="activity-stats">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Activity</h5>
                        <p class="card-text">
                            <span id="total-activity">{{ $totalActivity }}</span>
                            <span id="text-activity">&nbsp{{ Str::plural('Activity', $totalActivity) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Category</h5>
                        <p class="card-text">
                            <span id="total-category">{{ $totalCategory }}</span>
                            <span id="text-category">&nbsp{{ Str::plural('Category', $totalCategory) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="activity.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.activity.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="Kunjungan dan Bakti Sosial" aria-label="Kunjungan dan Bakti Sosial"
                                aria-describedby="name" value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="description">Description</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror"
                                placeholder="Program kepedulian sosial HIPMI TUP"
                                aria-label="Program kepedulian sosial HIPMI TUP" aria-describedby="description"
                                value="{{ old('description') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="image">Image</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="image" name="image"
                                class="form-control dt-image @error('image') is-invalid @enderror" accept="image/*"
                                aria-label="Product Image" aria-describedby="image" />
                        </div>
                        <div class="mt-3 text-center">
                            <div id="image-preview-container"
                                class="d-inline-block border rounded shadow-sm p-2 bg-light"
                                style="min-width:110px;min-height:110px;">
                                <img id="image-preview" src="#" alt="Image Preview" class="product-image d-none"
                                    style="max-width:100px;max-height:100px;object-fit:cover;border-radius:10%;"
                                    loading="lazy" />
                            </div>
                            <div id="image-preview-placeholder" class="text-muted small mt-2">
                                <i class="bi bi-image" style="font-size:2rem;"></i>
                                <div>No image selected</div>
                            </div>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="activity.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-name" class="form-control dt-name"
                                placeholder="Kunjungan dan Bakti Sosial" aria-label="Kunjungan dan Bakti Sosial"
                                aria-describedby="show-name" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-description">Description</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-description" class="form-control dt-description"
                                placeholder="Program kepedulian sosial HIPMI TUP"
                                aria-label="Program kepedulian sosial HIPMI TUP" aria-describedby="show-description"
                                disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-category">Category</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-category" class="form-control dt-category"
                                placeholder="Kunjungan dan Bakti Sosial" aria-label="Kunjungan dan Bakti Sosial"
                                aria-describedby="show-category" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-image">Image</label>
                        <div class="input-group input-group-merge">
                            <img id="show-image" class="img-fluid dt-image w-100 h-auto"
                                src="{{ asset('img/backgrounds/event.jpg') }}" alt="Product Image" loading="lazy" />
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="activity.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="Kunjungan dan Bakti Sosial" aria-label="Kunjungan dan Bakti Sosial"
                                aria-describedby="name" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-description">Description</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror"
                                placeholder="Program kepedulian sosial HIPMI TUP"
                                aria-label="Program kepedulian sosial HIPMI TUP" aria-describedby="description" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('category_id') is-invalid @enderror"
                                id="edit-category_id" name="category_id">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-image">Image</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="edit-image" name="image"
                                class="form-control dt-image @error('image') is-invalid @enderror" accept="image/*"
                                aria-label="Product Image" aria-describedby="image" />
                        </div>
                        <div class="mt-3 text-center">
                            <div id="image-preview-container-edit"
                                class="d-inline-block border rounded shadow-sm p-2 bg-light"
                                style="min-width:110px;min-height:110px;">
                                <img id="image-preview-edit" src="#" alt="Image Preview"
                                    class="product-image d-none"
                                    style="max-width:100px;max-height:100px;object-fit:cover;border-radius:10%;"
                                    loading="lazy" />
                            </div>
                            <div id="image-preview-placeholder-edit" class="text-muted small mt-2">
                                <i class="bi bi-image" style="font-size:2rem;"></i>
                                <div>No image selected</div>
                            </div>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="edit" />
        </x-dashboard.canvas.wrapper>

        <form class="d-inline" id="form-delete-record" method="DELETE" action="#"></form>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce>
        </script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"
            @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/lightbox/js/lightbox.min.js') }}" @cspNonce>
        </script>
        @canany(['activity.create', 'activity.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            lightbox.option({
                'resizeDuration': 500,
                'wrapAround': true
            })
        </script>
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['activity.create', 'activity.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    function setupImagePreview(inputId, previewId, placeholderId) {
                        const input = document.getElementById(inputId);
                        const preview = document.getElementById(previewId);
                        const placeholder = document.getElementById(placeholderId);

                        if (!input) return;

                        input.addEventListener('change', function() {
                            const file = input.files && input.files[0];
                            if (file) {
                                preview.src = URL.createObjectURL(file);
                                preview.classList.remove('d-none');
                                placeholder.classList.add('d-none');
                            } else {
                                preview.src = '#';
                                preview.classList.add('d-none');
                                placeholder.classList.remove('d-none');
                            }
                        });
                    }

                    setupImagePreview('image', 'image-preview', 'image-preview-placeholder');
                    setupImagePreview('edit-image', 'image-preview-edit', 'image-preview-placeholder-edit');
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    const select2Elements = $('.select2');
                    if (select2Elements.length) {
                        select2Elements.each(function() {
                            $(this).select2({
                                dropdownParent: $(this).closest('.offcanvas'),
                                placeholder: 'Select an option',
                                allowClear: false,
                                width: '100%',
                            });
                        });
                    }
                });
            </script>
        @endcanany
        @canany(['activity.create', 'activity.edit', 'activity.show', 'activity.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#activity-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.activity.update', ':id') }}",
                            destroy: "{{ route('dashboard.activity.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    name: '#show-name',
                                    description: '#show-description',
                                    label: '#show-category',
                                    image: '#show-image',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    label: function(el, data, rowData) {
                                        el.val(rowData.category.label);
                                    },
                                    image: function(el, data, rowData) {
                                        if (rowData.image_url) {
                                            el.attr('src', rowData.image_url);
                                        }
                                    }
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    name: '#edit-name',
                                    description: '#edit-description',
                                    label: '#edit-category_id',
                                    image: '#image-preview-edit',
                                },
                                fieldMapBehavior: {
                                    label: function(el, data, rowData) {
                                        el.val(rowData.category.id).trigger('change');
                                    },
                                    image: function(el, data, rowData) {
                                        if (rowData.image_url) {
                                            el.attr('src', rowData.image_url).removeClass('d-none');
                                            $('#image-preview-placeholder-edit').addClass('d-none');
                                        } else {
                                            el.addClass('d-none');
                                            $('#image-preview-placeholder-edit').removeClass('d-none');
                                        }
                                    }
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            const getPluralText = (count, singular, plural) => {
                                return "&nbsp" + (count === 1 ? singular : plural);
                            };

                            if (formId === '#form-add-new-record') {
                                $('#image-preview').attr('src', '#').addClass('d-none');
                                $('#image-preview-placeholder').removeClass('d-none');
                            }

                            const totalActivity = response.data.totalActivity || 0;
                            const totalCategory = response.data.totalCategory || 0;
                            $('#activity-stats #total-activity').text(totalActivity);
                            $('#activity-stats #text-activity').html(getPluralText(totalActivity, 'Activity',
                                'Activities'));
                            $('#activity-stats #total-category').text(totalCategory);
                            $('#activity-stats #text-category').html(getPluralText(totalCategory, 'Category',
                                'Categories'));

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: response.message || "Operation completed successfully.",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            } else {
                                alert(response.message || 'Operation completed successfully.');
                            }
                        }
                    });
                });
            </script>
        @endcanany
    @endPushOnce
</x-layouts.dashboard>
