<x-layouts.installation title="Setup" :errors="$errors ?? ''" step="3">
    <x-install.maintenance title="Test Connection & Configure" />

    <form action="{{ route('install.setup.store') }}" method="POST" class="mt-5">
        @csrf

        <div class="p-3">
            <h5 class="my-5 text-lg font-semibold text-neutral-800" data-aos="fade-right">
                General Configuration
            </h5>

            <div class="space-y-6 sm:space-y-5" data-aos="fade-up">
                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="app_url" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Application URL
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="app_url" type="text" name="app_url" value="{{ old('app_url', $guessedUrl) }}"
                            placeholder="https://subdomain.example.com/" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * This is the URL where you are installing the application,
                            for example, for subdomain in this field you need to enter "https://subdomain.example.com/",
                            make sure to check the documentation on how to create your subdomain.
                        </p>
                        @error('app_url')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="app_name" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Application Name
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="app_name" type="text" name="app_name" value="{{ old('app_name', $defaultConfig['app_name']) }}"
                            placeholder="Best Web Application Ever" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * This is the name of your application, it will be displayed in the header.
                        </p>
                        @error('app_name')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <h5 data-aos="fade-up" class="mb-5 mt-10 text-lg font-semibold text-neutral-800">
                Database Configuration
            </h5>

            @if ($errors->has('privilege'))
                <div class="mb-5 rounded-md border border-danger-200 bg-danger-50 p-4 text-sm text-danger-500"
                    data-aos="fade-up">
                    <p class="mb-2">{{ $errors->first('privilege') }}</p>
                    <p class="font-bold">
                        Make sure to give
                        <span class="font-bold">
                            all privileges to the database user
                        </span>
                        , check the installation video in the documentation
                    </p>
                </div>
            @endif

            @if ($errors->has('general'))
                <div class="mb-5 rounded-md border border-danger-200 bg-danger-50 p-4 text-sm text-danger-500"
                    data-aos="fade-up">
                    <p class="mb-2">{{ $errors->first('general') }}</p>
                    <p class="font-bold">Please check the following:</p>
                    <p class="list-disc list-inside">
                        <li>Database credentials are correct.</li>
                        <li>Database server is running.</li>
                        <li>Database server is reachable from the current server.</li>
                    </p>
                </div>
            @endif

            <div class="space-y-6 sm:space-y-5" data-aos="fade-up">
                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="db_host" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Database Host
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="db_host" type="text" name="db_host" value="{{ old('db_host', $defaultConfig['db_host']) }}"
                            placeholder="localhost" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * The database host is usually "localhost" or "127.0.0.1",
                            if you are using a different host, please enter the correct value.
                        </p>
                        @error('db_host')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="db_port" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Database Port
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="db_port" type="number" name="db_port" value="{{ old('db_port', $defaultConfig['db_port']) }}"
                            placeholder="3306" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * The default MySQL ports is 3306,
                            change the value only if you are certain that you are using different port.
                        </p>
                        @error('db_port')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="db_name" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Database Name
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="db_name" type="text" name="db_name" value="{{ old('db_name', $defaultConfig['db_name']) }}"
                            placeholder="laravel_installer_db" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Make sure that you have created the database before configuring.
                        </p>
                        @error('db_name')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="db_user" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Database User
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="db_user" type="text" name="db_user" value="{{ old('db_user', $defaultConfig['db_user']) }}"
                            placeholder="root" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Make sure you have set ALL privileges for the user.
                        </p>
                        @error('db_user')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="db_pass" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        Database Password
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="db_pass" type="password" name="db_pass" value="{{ old('db_pass') }}"
                            placeholder="password"
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * If you have set a password for the user, please enter it here.
                            For security reasons, it is recommended to set a password.
                        </p>
                        @error('db_pass')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="-m-7 mt-6 rounded-b border-t border-neutral-200 bg-neutral-50 p-4 px-10 text-right">
                <button type="submit"
                    class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-60 hover:bg-primary-700">
                    Test Connection &amp; Configure
                </button>
            </div>
        </div>
    </form>
</x-layouts.installation>
