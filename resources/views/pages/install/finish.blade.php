<x-layouts.installation title="Finish" :errors="$errors ?? ''" step="5">
    <div class="p-3">
        <h4 data-aos="fade-right" class="my-5 text-lg font-semibold text-neutral-800">
            Installation Completed
        </h4>

        <p class="text-neutral-700" data-aos="fade-right">
            <span class="font-semibold">{{ $appName }} has been successfully installed</span>
            , if you want to add data dummy to the database, you can run the
            command below:
        </p>

        <div class="mt-4 mb-3 block w-full dark:text-black rounded-md border border-neutral-300 bg-neutral-50 py-4 px-5 text-base shadow-sm"
            data-aos="fade-up">
            <span class="select-all">{{ $execPath }} {{ $base_path }}\artisan db:seed</span>
        </div>

        <p class="mt-4 text-neutral-700" data-aos="fade-up">
            If you are not certain on how to configure the database seeder with
            the minimum required PHP version ({{ $minPhpVersion }}), the best is to
            consult with experienced laravel developers.
        </p>

        <h4 class="mt-5 mb-2 text-lg font-semibold text-neutral-800" data-aos="fade-right">
            Setting up Task Scheduler
        </h4>

        <p class="text-neutral-700" data-aos="fade-up">
            To run the scheduler, you should add the following Cron entry to your
            server:
        </p>

        <div class="mt-4 mb-3 block w-full dark:text-black rounded-md border border-neutral-300 bg-neutral-50 py-4 px-5 text-base shadow-sm"
            data-aos="fade-up">
            * * * * *
            <span class="select-all">{{ $execPath }} {{ $base_path }}\artisan schedule:run >> /dev/null
                2>&1</span>
        </div>

        <p class="text-neutral-700" data-aos="fade-up">
            This Cron will call the Laravel command scheduler every minute. When
            the schedule:run command is executed, Laravel will evaluate your
            scheduled tasks and runs the tasks that are due.
        </p>

        <h4 class="mt-5 mb-2 text-lg font-semibold text-neutral-800" data-aos="fade-right">
            Admin Credentials
        </h4>

        <p data-aos="fade-up">
            <span class="font-semibold text-neutral-700">Name:</span>
            <span class="select-all dark:text-black">{{ $username || '-' }}</span>
            <br />
            <span class="font-semibold text-neutral-700">Role:</span>
            <span class="select-all dark:text-black">{{ ucfirst(config('rbac.role.highest')) }}</span>
            <br />
            <span class="font-semibold text-neutral-700">Password:</span>
            <span class="dark:text-black">Your chosen password</span>
        </p>

        <div class="-m-7 mt-6 rounded-b border-t border-neutral-200 bg-neutral-50 p-4 px-10 text-right">
            <button type="button" onClick="window.location.href='{{ url('/') }}'"
                class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 hover:bg-primary-700">
                Home
            </button>
        </div>
    </div>
</x-layouts.installation>
