@can($canvasPermission)
    <div class="offcanvas offcanvas-end" id="{{ $canvasId }}">
        {{ $slot }}
    </div>
@endcan
