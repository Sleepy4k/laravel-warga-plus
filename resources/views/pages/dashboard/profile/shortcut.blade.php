<x-layouts.dashboard title="User Shortcuts">
    @pushOnce('plugin-styles')
        <style @cspNonce>
            .shortcut-card {
                transition: box-shadow 0.2s;
            }

            .shortcut-card:hover {
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            }
        </style>
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            <button class="btn btn-primary" type="button" id="save-changes">
                Save Changes
            </button>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <x-profile.navbar />

                <div class="card mb-4">
                    <div class="d-flex justify-content-between align-items-center card-header">
                        <h5 class="mb-0">Your Shortcuts</h5>
                        <input type="text" id="user-shortcut-search" class="form-control w-50"
                            placeholder="Search your shortcuts..." style="max-width:300px;">
                    </div>
                    <div class="card-body">
                        <div class="row mb-5" id="user-shortcuts">
                            <div class="col-12 text-center d-none" id="user-shortcut-notfound"><em>Data not found.</em>
                            </div>
                            @forelse    ($userShortcuts as $shortcut)
                                @if (!empty($shortcut->permissions))
                                    @canany($shortcut->permissions)
                                        <div class="col-md-3 mb-3 selected-shortcut user-shortcut-search-item"
                                            data-label="{{ strtolower($shortcut->label) }}">
                                            <div class="card shadow-sm shortcut-card">
                                                <div class="card-body text-center">
                                                    <div class="mb-2">
                                                        <i class="bx bx-{{ $shortcut->icon ?? 'external-link' }} fa-2x"></i>
                                                    </div>
                                                    <h5 class="card-title">{{ $shortcut->label }}</h5>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button class="btn btn-danger btn-sm shortcut-remove mb-2"
                                                            data-id="{{ $shortcut->id }}">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcanany
                                @else
                                    <div class="col-md-3 mb-3 selected-shortcut user-shortcut-search-item"
                                        data-label="{{ strtolower($shortcut->label) }}">
                                        <div class="card shadow-sm shortcut-card">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    <i class="bx bx-{{ $shortcut->icon ?? 'external-link' }} fa-2x"></i>
                                                </div>
                                                <h5 class="card-title">{{ $shortcut->label }}</h5>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-danger btn-sm shortcut-remove mb-2"
                                                        data-id="{{ $shortcut->id }}">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="col-12 text-center"><em>No shortcuts added yet.</em></div>
                            @endforelse
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Add More Shortcuts</h5>
                            <input type="text" id="shortcut-search" class="form-control w-50"
                                placeholder="Search shortcuts..." style="max-width:300px;">
                        </div>
                        <div class="row" id="available-shortcuts">
                            <div class="col-12 text-center d-none" id="available-shortcut-notfound"><em>Data not
                                    found.</em></div>
                            @foreach ($shortcuts as $shortcut)
                                @php
                                    $isAdded = $userShortcuts->contains('id', $shortcut->id);
                                @endphp

                                <div class="col-md-3 mb-3 shortcut-search-item {{ $isAdded ? 'card-selected' : '' }}"
                                    data-label="{{ strtolower($shortcut->label) }}">
                                    <div class="card shortcut-card">
                                        <div class="card-body text-center available-shortcut">
                                            <div class="mb-2">
                                                <i class="bx bx-{{ $shortcut->icon ?? 'external-link' }} fa-2x"></i>
                                            </div>
                                            <h5 class="card-title">{{ $shortcut->label }}</h5>
                                            <div class="d-flex justify-content-center gap-2">
                                                @if (!$isAdded)
                                                    <button class="btn btn-success btn-sm shortcut-add mb-2"
                                                        data-id="{{ $shortcut->id }}">Add</button>
                                                @else
                                                    <button class="btn btn-secondary btn-sm mb-2"
                                                        data-id="{{ $shortcut->id }}" disabled>Added</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('page-scripts')
        <script @cspNonce>
            $(document).ready(function() {
                $('#save-changes').on('click', function() {
                    Swal.fire({
                        title: 'Save Changes',
                        text: 'Are you sure you want to save changes to your shortcuts?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Saving Changes...",
                                text: "Please wait while your changes are being saved.",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                            setTimeout(() => {
                                Swal.close();
                                Swal.fire('Success', 'Your shortcut changes have been saved.',
                                    'success');
                                location.reload();
                            }, 1000);
                        }
                    });
                });
            });
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                const setupSearchFilter = (searchInputId, containerSelector, itemSelector, notFoundId) => {
                    $(searchInputId).on('keyup', function() {
                        const value = $(this).val().toLowerCase();
                        let found = false;

                        $(`${containerSelector} ${itemSelector}`).each(function() {
                            const isHidden = $(this).is(':hidden') && !$(this).hasClass(
                                'search-disabled');
                            if (isHidden) return;

                            const match = $(this).data('label').includes(value);
                            $(this).toggle(match).addClass('search-disabled');
                            if (match) found = true;
                        });

                        $(notFoundId).toggleClass('d-none', found);
                    });
                };

                setupSearchFilter('#shortcut-search', '#available-shortcuts', '.shortcut-search-item',
                    '#available-shortcut-notfound');
                setupSearchFilter('#user-shortcut-search', '#user-shortcuts', '.user-shortcut-search-item',
                    '#user-shortcut-notfound');
            });
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                const handleAddButtonClick = function() {
                    var shortcutId = $(this).data('id');
                    Swal.fire({
                        title: "Adding Shortcut...",
                        text: "Please wait while the shortcut is being added.",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    $.ajax({
                        url: "{{ route('profile.shortcut.update', ':id') }}".replace(':id', shortcutId),
                        type: 'PUT',
                        dataType: 'json',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            Swal.close();
                            if (data.status == 'success') {
                                if ($('#user-shortcuts .selected-shortcut').length === 0) {
                                    $('#user-shortcuts').html('');
                                }

                                var newCard = `<div class="col-md-3 mb-3 selected-shortcut">
                                    <div class="card shadow-sm shortcut-card">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <i class="bx bx-${data.data.icon} fa-2x"></i>
                                            </div>
                                            <h5 class="card-title">${data.data.label}</h5>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-danger btn-sm shortcut-remove mb-2"
                                                    data-id="${data.data.id}">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                                $('#user-shortcuts').append(newCard);
                                $(`button[data-id="${shortcutId}"]`).closest(
                                        '.available-shortcut button')
                                    .removeClass('btn-success shortcut-add')
                                    .addClass('btn-secondary').attr('disabled', true).text('Added')
                                    .closest('.shortcut-search-item').hide()
                                    .removeClass('search-disabled');

                                if ($('#available-shortcuts .shortcut-search-item:not(:hidden)')
                                    .length === 0) {
                                    $('#available-shortcut-notfound').removeClass('d-none');
                                }
                            } else {
                                Swal.fire('Error', data.message || 'Failed to add shortcut', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.status === 409 ? 'Shortcut already exists.' :
                                'Failed to add shortcut', 'error');
                        }
                    });
                };

                const handleRemoveButtonClick = function() {
                    var shortcutId = $(this).data('id');
                    Swal.fire({
                        title: "Removing Shortcut...",
                        text: "Please wait while the shortcut is being removed.",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    $.ajax({
                        url: "{{ route('profile.shortcut.destroy', ':id') }}".replace(':id',
                            shortcutId),
                        type: 'DELETE',
                        dataType: 'json',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            Swal.close();
                            if (data.status == 'success') {
                                if ($('#available-shortcuts .shortcut-search-item:not(:hidden)')
                                    .length === 0) {
                                    $('#available-shortcut-notfound').addClass('d-none');
                                }

                                $(`button[data-id="${shortcutId}"]`).closest('.selected-shortcut')
                                    .remove();
                                $(`.available-shortcut button[data-id="${shortcutId}"]`).removeClass(
                                        'btn-secondary')
                                    .addClass('btn-success shortcut-add').removeAttr('disabled').text(
                                        'Add')
                                    .closest('.shortcut-search-item').show();

                                if ($('#user-shortcuts .selected-shortcut').length === 0) {
                                    $('#user-shortcuts').html(
                                        '<div class="col-12 text-center"><em>No shortcuts added yet.</em></div>'
                                    );
                                }
                            } else {
                                Swal.fire('Error', data.message || 'Failed to remove shortcut',
                                    'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to remove shortcut', 'error');
                        }
                    });
                };

                $(document).on('click', '.shortcut-add', handleAddButtonClick);
                $(document).on('click', '.shortcut-remove', handleRemoveButtonClick);
                $('#available-shortcuts .card-selected').each(function() {
                    $(this).hide();
                    $(this).removeClass('card-selected');
                    $(this).closest('.shortcut-search-item').hide();
                });
                setTimeout(() => {
                    if ($('#available-shortcuts .shortcut-search-item:not(:hidden)').length === 0) {
                        $('#available-shortcut-notfound').removeClass('d-none');
                    }
                }, 500);
            });
        </script>
    @endPushOnce
</x-layouts.dashboard>
