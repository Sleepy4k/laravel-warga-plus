<x-layouts.dashboard title="Article">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/lightbox/css/lightbox.min.css') }}" @cspNonce />
        <style @cspNonce>
            .article-image {
                max-width: 100px;
                max-height: 100px;
                border-radius: 10%;
                object-fit: cover;
            }
        </style>
        @canany(['activity.create', 'activity.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
            <link rel="stylesheet" href="{{ asset('vendor/quill/css/quill.snow.css') }}" @cspNonce />
            <link rel="stylesheet" href="{{ asset('vendor/quill/css/katex.min.css') }}" @cspNonce />
            <style @cspNonce>
                .form-editor-quill {
                    position: relative;
                    width: 100%;
                }

                #full-editor .ql-container .ql-snow {
                    height: auto;
                    min-height: 200px;
                    width: 100%;
                }

                #full-editor .ql-editor {
                    white-space: pre-wrap;
                    word-break: break-word;
                    padding: 12px;
                    width: 100%;
                    box-sizing: border-box;
                }
            </style>
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('article.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Article
                </button>
            @endcan
        </div>

        <div class="row" id="article-stats">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Article</h5>
                        <p class="card-text">
                            <span id="total-article">{{ $totalArticle }}</span>
                            <span id="text-article">&nbsp{{ Str::plural('Article', $totalArticle) }}</span>
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

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="article.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.article.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Membuat Inkubasi Bisnis Berbasis Teknologi"
                                aria-label="Membuat Inkubasi Bisnis Berbasis Teknologi" aria-describedby="title"
                                value="{{ old('title') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="categories">Categories</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('categories') is-invalid @enderror"
                                id="categories" name="categories[]" multiple>
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
                                    style="max-width:100px;max-height:100px;object-fit:cover;border-radius:10%;" loading="lazy" />
                            </div>
                            <div id="image-preview-placeholder" class="text-muted small mt-2">
                                <i class="bi bi-image" style="font-size:2rem;"></i>
                                <div>No image selected</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="excerpt">Excerpt</label>
                        <div class="input-group input-group-merge">
                            <textarea id="excerpt" name="excerpt" class="form-control dt-excerpt @error('excerpt') is-invalid @enderror"
                                placeholder="Membuat Inkubasi Bisnis Berbasis Teknologi adalah langkah penting dalam mengembangkan inovasi dan menciptakan peluang bisnis yang berkelanjutan."
                                rows="5">{{ old('excerpt') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 form-editor-quill">
                        <label class="form-label" for="content">Content</label>
                        <div id="full-editor">{!! old('content') !!}</div>
                        <div class="input-group input-group-merge">
                            <input type="hidden" id="content" name="content" value="{{ old('content') }}">
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="article.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-title" class="form-control dt-title"
                                placeholder="Membuat Inkubasi Bisnis Berbasis Teknologi"
                                aria-label="Membuat Inkubasi Bisnis Berbasis Teknologi" aria-describedby="show-title"
                                readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-slug">Slug</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-slug" class="form-control dt-slug"
                                placeholder="membuat-inkubasi-bisnis-berbasis-teknologi"
                                aria-label="membuat-inkubasi-bisnis-berbasis-teknologi" aria-describedby="show-slug"
                                readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-categories">Categories</label>
                        <div id="show-categories" class="mb-2"></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-image">Image</label>
                        <div class="input-group input-group-merge">
                            <img id="show-image" class="img-fluid dt-image w-100 h-auto"
                                src="{{ asset('img/backgrounds/event.jpg') }}" alt="Article Image" loading="lazy" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-excerpt">Excerpt</label>
                        <textarea id="show-excerpt" class="form-control dt-excerpt"
                            placeholder="Membuat Inkubasi Bisnis Berbasis Teknologi adalah langkah penting dalam mengembangkan inovasi dan menciptakan peluang bisnis yang berkelanjutan."
                            rows="5" readonly disabled></textarea>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="article.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#"
                    enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-title"
                                class="form-control dt-title @error('title') is-invalid @enderror"
                                placeholder="Membuat Inkubasi Bisnis Berbasis Teknologi"
                                aria-label="Membuat Inkubasi Bisnis Berbasis Teknologi" aria-describedby="edit-title"
                                name="title" value="{{ old('title') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-categories">Categories</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('categories') is-invalid @enderror"
                                id="edit-categories" name="categories[]" multiple>
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
                                aria-label="Product Image" aria-describedby="edit-image" />
                        </div>
                        <div class="mt-3 text-center">
                            <div id="image-preview-container-edit"
                                class="d-inline-block border rounded shadow-sm p-2 bg-light"
                                style="min-width:110px;min-height:110px;">
                                <img id="image-preview-edit" src="#" alt="Image Preview"
                                    class="product-image d-none"
                                    style="max-width:100px;max-height:100px;object-fit:cover;border-radius:10%;" loading="lazy" />
                            </div>
                            <div id="image-preview-placeholder-edit" class="text-muted small mt-2">
                                <i class="bi bi-image" style="font-size:2rem;"></i>
                                <div>No image selected</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-excerpt">Excerpt</label>
                        <textarea id="edit-excerpt" class="form-control @error('excerpt') is-invalid @enderror" name="excerpt"
                            rows="3">{{ old('excerpt') }}</textarea>
                    </div>
                    <div class="col-sm-12 form-editor-quill">
                        <label class="form-label" for="edit-content">Content</label>
                        <div id="full-editor-edit" class="form-editor-quill">{!! old('content') !!}</div>
                        <div class="input-group input-group-merge">
                            <input type="hidden" id="edit-content" name="content" value="{{ old('content') }}">
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
        @canany(['article.create', 'article.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/quill/js/highlight.min.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/quill/js/quill.min.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/quill/js/katex.min.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            lightbox.option({
                'resizeDuration': 500,
                'wrapAround': true
            });
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                $(document).on('click', '.view-content', function() {
                    var articleId = $(this).data('id');
                    window.location.href = "{{ route('dashboard.article.preview', ':id') }}"
                        .replace(':id', articleId);
                });
            });
        </script>
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['article.create', 'article.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#excerpt'));
                    autosize($('#edit-excerpt'));
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
                let quill = null;
                let quillEdit = null;

                $(document).ready(function() {
                    const toolbarOptions = [
                        [{
                            font: []
                        }, {
                            size: []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            script: 'super'
                        }, {
                            script: 'sub'
                        }],
                        [{
                            header: '1'
                        }, {
                            header: '2'
                        }, 'blockquote', 'code-block'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }, {
                            indent: '-1'
                        }, {
                            indent: '+1'
                        }],
                        [{
                            direction: 'rtl'
                        }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ];

                    const quillOptions = {
                        bounds: '#full-editor',
                        placeholder: 'Type Something...',
                        modules: {
                            toolbar: toolbarOptions,
                            syntax: true,
                            history: {
                                delay: 1000,
                                maxStack: 500,
                                userOnly: true
                            }
                        },
                        theme: 'snow'
                    };

                    quill = new Quill('#full-editor', quillOptions);
                    quillEdit = new Quill('#full-editor-edit', quillOptions);

                    function debounce(func, wait) {
                        let timeout;
                        return function(...args) {
                            clearTimeout(timeout);
                            timeout = setTimeout(() => func.apply(this, args), wait);
                        };
                    }

                    const updateContent = debounce(function() {
                        const content = quill.root.innerHTML;
                        $('#content').val(content);
                    }, 300);

                    const updateContentEdit = debounce(function() {
                        const content = quillEdit.root.innerHTML;
                        $('#edit-content').val(content);
                    }, 300);

                    quill.on('text-change', updateContent);
                    quillEdit.on('text-change', updateContentEdit);
                });
            </script>
        @endcanany
        @canany(['article.create', 'article.edit', 'article.show', 'article.delete'])
            <script src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#article-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.article.update', ':id') }}",
                            destroy: "{{ route('dashboard.article.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    title: '#show-title',
                                    slug: '#show-slug',
                                    categories: '#show-categories',
                                    image: '#show-image',
                                    excerpt: '#show-excerpt',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    categories: function(el, data, rowData) {
                                        el.html(data);
                                    },
                                    image: function(el, data, rowData) {
                                        if (rowData.image_url) {
                                            el.attr('src', rowData.image_url).removeClass('d-none');
                                        }
                                    }
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    title: '#edit-title',
                                    excerpt: '#edit-excerpt',
                                    content: '#edit-content',
                                    categories: '#edit-categories',
                                    image: '#image-preview-edit',
                                },
                                fieldMapBehavior: {
                                    content: function(el, data) {
                                        el.val(data);

                                        if (quillEdit) {
                                            quillEdit.setContents(quillEdit.clipboard.convert({
                                                html: data || ''
                                            }), 'silent');
                                        }
                                    },
                                    categories: function(el, data, rowData) {
                                        if (rowData.categories_plain && rowData.categories_plain.length > 0) {
                                            el.val(rowData.categories_plain).trigger('change');
                                        } else {
                                            el.val([]).trigger('change');
                                        }
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

                            const totalArticle = response.data.totalArticle || 0;
                            const totalCategory = response.data.totalCategory || 0;
                            $('#article-stats #total-article').text(totalArticle);
                            $('#article-stats #text-article').html(getPluralText(totalArticle, 'Article',
                                'Articles'));
                            $('#article-stats #total-category').text(totalCategory);
                            $('#article-stats #text-category').html(getPluralText(totalCategory, 'Category',
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
