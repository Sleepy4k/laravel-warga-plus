<x-layouts.installation title="User" :errors="$errors ?? ''" step="4">
    <x-install.maintenance title="Install" />

    <form action="{{ route('install.user.store') }}" method="POST" class="mt-5">
        @csrf

        <div class="p-3">
            <h5 class="my-5 text-lg font-semibold text-neutral-800" data-aos="fade-right">
                Configure Admin User Account
            </h5>

            <div class="space-y-6 sm:space-y-5" data-aos="fade-up">
                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="name" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Name (Full Name)
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                            placeholder="John Doe" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Enter your full name, this will be used as the admin name.
                        </p>
                        @error('name')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="username" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Username
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="username" type="text" name="username" value="{{ old('username') }}"
                            placeholder="johndoe" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Enter your username, this will be used as the admin username.
                        </p>
                        @error('username')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="email" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        E-Mail Address
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="putri@example.com" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Enter your email address, this will be used as the admin email.
                        </p>
                        @error('email')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="password" class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Password
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="password" type="password" name="password" placeholder="********" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Enter your password, this will be used as the admin password.
                        </p>
                        @error('password')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-neutral-200 sm:pt-5">
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-neutral-700 sm:mt-px sm:pt-2">
                        <span class="mr-1 text-sm text-danger-600">*</span>
                        Confirm Password
                    </label>
                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="********" required
                            class="block w-full rounded-lg border border-neutral-300 dark:text-black shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2" />
                        <p class="mt-2 text-sm text-neutral-500">
                            * Confirm your password, this will be used as the admin password.
                        </p>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="-m-7 mt-6 rounded-b border-t border-neutral-200 bg-neutral-50 p-4 px-10 text-right">
                <button type="submit"
                    class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-60 hover:bg-primary-700">
                    Install
                </button>
            </div>
        </div>
    </form>
</x-layouts.installation>
