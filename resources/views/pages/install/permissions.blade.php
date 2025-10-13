<x-layouts.installation title="Permissions" :errors="$errors ?? ''" step="2">
    <div class="p-3" data-aos="fade-right">
        <h4 class="my-5 text-lg font-semibold text-neutral-800">
            Files and folders permissions
        </h4>

        <p class="text-neutral-700 mb-[3vh]">
            These folders must be writable by web server using user:
            <strong class="select-all">{{ $process_user }}</strong>
            <br />
            Recommended permissions:
            <strong class="select-all">0775 or 0777</strong>
        </p>

        <x-install.table.box>
            <x-install.table.head.wrapper>
                <x-install.table.head.content>Extension</x-install.table.head.content>
                <x-install.table.head.content>Enabled</x-install.table.head.content>
            </x-install.table.head.wrapper>

            <x-install.table.body.wrapper>
                @foreach ($permissions['results'] as $permission)
                    <x-install.table.body.row>
                        <x-install.table.body.content class="font-medium">
                            {{ rtrim($permission['folder'], '/') }}
                        </x-install.table.body.content>
                        <x-install.table.body.content>
                            <span class="{{ $permission['isSet'] ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                @if ($permission['isSet'])
                                    <x-install.icon.passes />
                                @else
                                    <x-install.icon.error />
                                @endif
                                {{ $permission['permission'] }}
                            </span>
                        </x-install.table.body.content>
                    </x-install.table.body.row>
                @endforeach
            </x-install.table.body.wrapper>
        </x-install.table.box>

        @if ($permissions['errors'])
            <x-install.button-nav.error />
        @else
            <x-install.button-nav.success url="{{ route('install.setup') }}" />
        @endif
    </div>
</x-layouts.installation>
