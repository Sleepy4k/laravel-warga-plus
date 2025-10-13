<h4 class="fw-bold py-3 mb-0">
    @foreach ($breadcrumbs as $index => $breadcrumb)
        <span class="fw-light {{ $index === count($breadcrumbs) - 1 ? '' : 'text-muted' }}">
            {{ $breadcrumb['name'] }}{{ $index !== count($breadcrumbs) - 1 ? ' /' : '' }}
        </span>
    @endforeach
</h4>
