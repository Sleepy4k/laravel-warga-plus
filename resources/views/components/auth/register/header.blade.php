<div class="bs-stepper-header border-bottom-0">
    @foreach ($steps as $step)
        <div class="step" data-target="#{{ $step['target'] }}">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-circle">
                    <i class="bx bx-{{ $step['icon'] }}"></i>
                </span>
                <span class="bs-stepper-label mt-1">
                    <span class="bs-stepper-title">{{ ucfirst($step['title']) }}</span>
                    <span class="bs-stepper-subtitle">{{ $step['description'] }}</span>
                </span>
            </button>
        </div>

        @if (!$loop->last)
            <div class="line">
                <i class="bx bx-chevron-right"></i>
            </div>
        @endif
    @endforeach
</div>
