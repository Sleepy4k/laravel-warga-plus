<x-layouts.installation title="Requirements" :errors="$errors ?? ''">
    <div class="p-3">
        <x-install.table.title title="PHP Version" />

        <x-install.table.box>
            <x-install.table.head.wrapper>
                <x-install.table.head.content>Required PHP Version</x-install.table.head.content>
                <x-install.table.head.content>Current</x-install.table.head.content>
            </x-install.table.head.wrapper>

            <x-install.table.body.wrapper>
                <x-install.table.body.row>
                    <x-install.table.body.content class="font-medium">
                        {{ $php['minimum'] }} or higher
                    </x-install.table.body.content>
                    <x-install.table.body.content>
                        <span class="{{ $php['supported'] ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                            <x-dynamic-component :component="$php['supported'] ? 'install.icon.passes' : 'install.icon.error'" />
                            {{ $php['current'] }}
                        </span>
                    </x-install.table.body.content>
                </x-install.table.body.row>
            </x-install.table.body.wrapper>
        </x-install.table.box>

        <x-install.table.title title="Required PHP Extensions" class="mt-10" />

        <x-install.table.box>
            <x-install.table.head.wrapper>
                <x-install.table.head.content>Extension</x-install.table.head.content>
                <x-install.table.head.content>Enabled</x-install.table.head.content>
            </x-install.table.head.wrapper>

            <x-install.table.body.wrapper>
                @foreach ($requirements['results']['php'] as $requirement => $enabled)
                    <x-install.table.body.row>
                        <x-install.table.body.content class="font-medium">
                            {{ $requirement }}
                        </x-install.table.body.content>
                        <x-install.table.body.content>
                            <span class="{{ $enabled ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                <x-dynamic-component :component="$enabled ? 'install.icon.passes' : 'install.icon.error'" />
                                {{ $enabled ? 'Yes' : 'No' }}
                            </span>
                        </x-install.table.body.content>
                    </x-install.table.body.row>
                @endforeach
            </x-install.table.body.wrapper>
        </x-install.table.box>

        <x-install.table.title title="Required PHP Functions" class="mt-10" />

        <x-install.table.box>
            <x-install.table.head.wrapper>
                <x-install.table.head.content>Function</x-install.table.head.content>
                <x-install.table.head.content>Enabled</x-install.table.head.content>
            </x-install.table.head.wrapper>

            <x-install.table.body.wrapper>
                @foreach ($requirements['results']['functions'] as $func => $enabled)
                    <x-install.table.body.row>
                        <x-install.table.body.content class="font-medium">
                            {{ $func }}
                        </x-install.table.body.content>
                        <x-install.table.body.content>
                            <span class="{{ $enabled ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                <x-dynamic-component :component="$enabled ? 'install.icon.passes' : 'install.icon.error'" />
                                {{ $enabled ? 'Yes' : 'No' }}
                            </span>
                        </x-install.table.body.content>
                    </x-install.table.body.row>
                @endforeach
            </x-install.table.body.wrapper>
        </x-install.table.box>

        <x-install.table.title title="Recommended PHP Extensions/Functions" class="mt-10" />

        <x-install.table.box>
            <x-install.table.head.wrapper>
                <x-install.table.head.content>Requirement</x-install.table.head.content>
                <x-install.table.head.content>Enabled</x-install.table.head.content>
            </x-install.table.head.wrapper>

            <x-install.table.body.wrapper>
                @foreach ($requirements['recommended']['php'] as $requirement => $enabled)
                    <x-install.table.body.row>
                        <x-install.table.body.content class="font-medium">
                            {{ $requirement }} <span class="text-xs text-neutral-400">(ext)</span>
                        </x-install.table.body.content>
                        <x-install.table.body.content>
                            <span class="{{ $enabled ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                <x-dynamic-component :component="$enabled ? 'install.icon.passes' : 'install.icon.error'" />
                                {{ $enabled ? 'Yes' : 'No' }}
                            </span>
                        </x-install.table.body.content>
                    </x-install.table.body.row>
                @endforeach

                @foreach ($requirements['recommended']['functions'] as $func => $enabled)
                    <x-install.table.body.row>
                        <x-install.table.body.content class="font-medium">
                            {{ $func }} <span class="text-xs text-neutral-400">(func)</span>
                        </x-install.table.body.content>
                        <x-install.table.body.content>
                            <span class="{{ $enabled ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                <x-dynamic-component :component="$enabled ? 'install.icon.passes' : 'install.icon.error'" />
                                {{ $enabled ? 'Yes' : 'No' }}
                            </span>
                        </x-install.table.body.content>
                    </x-install.table.body.row>
                @endforeach
            </x-install.table.body.wrapper>
        </x-install.table.box>

        @if (($requirements['errors'] && $requirements['errors'] == true) || $php['supported'] == false)
            <x-install.button-nav.error />
        @else
            <x-install.button-nav.success url="{{ route('install.permissions') }}" />
        @endif
    </div>
</x-layouts.installation>
