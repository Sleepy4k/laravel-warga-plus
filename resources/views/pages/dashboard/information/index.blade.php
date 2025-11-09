<x-layouts.dashboard title="Information">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['information.create', 'information.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('information.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Add Information
                </button>
            @endcan
        </div>

        <div class="row" id="information-stats">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Informations</h5>
                        <p class="card-text">
                            <span id="total-information">{{ $total_informations }}</span>
                            <span id="text-information">&nbsp{{ Str::plural('Information', $total_informations) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Informations</h5>
                        <p class="card-text">
                            <span id="monthly-informations">{{ $monthly_informations }}</span>
                            <span id="text-monthly-informations">&nbsp{{ Str::plural('Report', $monthly_informations) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daily Informations</h5>
                        <p class="card-text">
                            <span id="daily-informations">{{ $daily_informations }}</span>
                            <span id="text-daily-informations">&nbsp{{ Str::plural('Report', $daily_informations) }}</span>
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

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="information.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.information.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Sampah Berserakan di Jalan Raya" aria-label="Sampah Berserakan di Jalan Raya"
                                aria-describedby="title" value="{{ old('title') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="content">Content</label>
                        <div class="input-group input-group-merge">
                            <textarea id="content" name="content"
                                class="form-control dt-content @error('content') is-invalid @enderror"
                                placeholder="Saya ingin melaporkan adanya sampah berserakan di jalan raya yang mengganggu kenyamanan warga sekitar."
                                aria-label="Saya ingin melaporkan adanya sampah berserakan di jalan raya yang mengganggu kenyamanan warga sekitar."
                                aria-describedby="content" rows="3">{{ old('content') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select id="category_id" name="category_id"
                                class="form-select select2 dt-category @error('category_id') is-invalid @enderror">
                                <option value="" disabled selected>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ strtolower($category->name) == "lainnya" ? 'other' : $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ ucfirst(strtolower($category->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" id="new-category-group">
                        <label class="form-label" for="new-category">New Category</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="new-category"
                                class="form-control dt-new-category @error('new-category') is-invalid @enderror" name="new-category"
                                placeholder="Lingkungan" aria-label="Lingkungan"
                                aria-describedby="new-category" value="{{ old('new-category', "") }}" />
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="report.category.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-title" class="form-control dt-title" placeholder="Sampah Berserakan di Jalan Raya"
                                aria-label="Sampah Berserakan di Jalan Raya" aria-describedby="show-title" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-content">Content</label>
                        <div class="input-group input-group-merge">
                            <textarea id="show-content" name="content"
                                class="form-control dt-content" placeholder="Deskripsi dokumen"
                                aria-label="Deskripsi dokumen" aria-describedby="show-content" rows="3" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-category_id" class="form-control dt-category" placeholder="Lingkungan"
                                aria-label="Lingkungan" aria-describedby="show-category_id" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-user">Informant</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-user" class="form-control dt-user" placeholder="John Doe"
                                aria-label="John Doe" aria-describedby="show-user" disabled />
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="information.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Sampah Berserakan di Jalan Raya" aria-label="Sampah Berserakan di Jalan Raya"
                                aria-describedby="title" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-content">Content</label>
                        <div class="input-group input-group-merge">
                            <textarea id="edit-content" name="content"
                                class="form-control dt-content @error('content') is-invalid @enderror" placeholder="Deskripsi dokumen"
                                aria-label="Deskripsi dokumen" aria-describedby="content"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-category_id" name="category_id"
                                class="form-select select2 dt-category @error('category_id') is-invalid @enderror">
                                <option value="" disabled selected>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ strtolower($category->name) == "lainnya" ? 'other' : $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ ucfirst(strtolower($category->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" id="edit-new-category-group">
                        <label class="form-label" for="edit-new-category">New Category</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-new-category"
                                class="form-control dt-new-category @error('new-category') is-invalid @enderror" name="new-category"
                                placeholder="Lingkungan" aria-label="Lingkungan"
                                aria-describedby="new-category" value="{{ old('new-category', "") }}" />
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
        @canany(['information.create', 'information.edit'])
            <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['information.create', 'information.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#content'));
                    autosize($('#edit-content'));
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
            <script @cspNonce>
                $(document).ready(function() {
                    function toggleFormFields(prefix) {
                        const $category = $(`#${prefix}category_id`);
                        const $newCategory = $(`#${prefix}new-category`);

                        function updateVisibility() {
                            const isOther = $category.val() === 'other';

                            $newCategory.closest('.col-sm-12').toggle(isOther);
                        }

                        updateVisibility();

                        $category.off('change.toggleFields').on('change.toggleFields', function() {
                            if ($(this).val() === 'other') {
                                $newCategory.val('');
                            }
                            updateVisibility();
                        });
                    }

                    toggleFormFields('');
                    toggleFormFields('edit-');
                });
            </script>
        @endcanany
        @canany(['information.create', 'information.edit', 'information.show', 'information.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#information-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.information.update', ':id') }}",
                            destroy: "{{ route('dashboard.information.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    title: '#show-title',
                                    content: '#show-content',
                                    category_id: '#show-category_id',
                                    user: '#show-user',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {}
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    title: '#edit-title',
                                    content: '#edit-content',
                                    category_id: '#edit-category_id',
                                    'new-category': '#edit-new-category',
                                },
                                fieldMapBehavior: {
                                    category_id: function(el, data, rowData) {
                                        const isOther = !isNaN(data) ? false : data === 'other';
                                        el.val(isOther ? 'other' : data).trigger('change');
                                    },
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            const getPluralText = (count, singular, plural) => {
                                return "&nbsp" + (count === 1 ? singular : plural);
                            };

                            const totalInformations = response.data.total_informations || 0;
                            const monthlyInformations = response.data.monthly_informations || 0;
                            const dailyInformations = response.data.daily_informations || 0;
                            $('#total-report').text(totalInformations);
                            $('#text-report').html(getPluralText(totalInformations, 'Information', 'Informations'));
                            $('#reports-processed').text(monthlyInformations);
                            $('#text-reports-processed').html(getPluralText(monthlyInformations, 'Information', 'Informations'));
                            $('#reports-declined').text(dailyInformations);
                            $('#text-reports-declined').html(getPluralText(dailyInformations, 'Information', 'Informations'));

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
