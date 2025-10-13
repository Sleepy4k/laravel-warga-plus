<nav>
    <ol class="divide-y divide-neutral-300 rounded-md border border-neutral-300 md:flex md:divide-y-0">
        @foreach (['Requirements', 'Permissions', 'Setup', 'User', 'Finish'] as $index => $label)
            @php
                $currentStep = $index + 1;
                $isCompleted = $step > $currentStep;
                $isActive = $step == $currentStep;
            @endphp
            <li class="relative md:flex md:flex-1" data-aos="fade-right" data-aos-delay="{{ $currentStep * 100 }}">
                <a href="#" class="group pointer-events-none flex items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="{{ $isActive ? 'border-2 border-primary-600' : ($isCompleted ? 'bg-primary-600' : 'border-2 border-neutral-300') }} flex h-10 w-10 shrink-0 items-center justify-center rounded-full">
                            @if ($isCompleted)
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <span class="{{ $isActive ? 'text-primary-600' : 'text-neutral-500' }}">{{ str_pad($currentStep, 2, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </span>
                        <span class="ml-4 text-sm font-medium {{ $isActive ? 'text-primary-600' : 'text-neutral-500' }}">
                            {{ $label }}
                        </span>
                    </span>
                </a>
                @if ($currentStep < 5)
                    <div class="absolute top-0 right-0 hidden h-full w-5 md:block" aria-hidden="true">
                        <svg class="h-full w-full text-neutral-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
                            <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor" stroke-linejoin="round" />
                        </svg>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
