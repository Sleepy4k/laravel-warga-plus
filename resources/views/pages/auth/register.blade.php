<x-layouts.auth title="Register">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bs-stepper/bs-stepper.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" @cspNonce />
    @endPushOnce

    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-4 align-items-center justify-content-end p-5 pe-0">
                <div class="w-px-400">
                    <img src="{{ asset('img/illustrations/create-account-dark.png') }}" class="img-fluid"
                        alt="multi-steps" width="600" data-app-dark-img="illustrations/create-account-dark.png"
                        data-app-light-img="illustrations/create-account-light.png" loading="lazy" />
                </div>
            </div>

            <div class="d-flex col-lg-8 align-items-center justify-content-center authentication-bg p-sm-5 p-3">
                <div class="w-px-700">
                    <div id="multiStepsValidation" class="bs-stepper shadow-none">
                        <x-auth.register.header />

                        <div class="bs-stepper-content">
                            <form id="multiStepsForm" action="{{ route('register.store') }}" method="POST">
                                @csrf

                                <x-auth.register.account-detail />

                                <x-auth.register.personal-info />

                                <x-auth.register.agreement-tos />
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/libs/bs-stepper/bs-stepper.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script type="text/javascript" src="{{ asset('js/pages/auth-multisteps.min.js') }}" @cspNonce></script>
        <script type="text/javascript" @cspNonce>
            window.Helpers.initCustomOptionCheck();
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                var select2 = $('.select2');
                select2.length && select2.each(function() {
                    var e = $(this);
                    e.select2({
                        dropdownParent: e.parent(),
                        placeholder: 'Select an option',
                        allowClear: false,
                    });
                });
            });
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                var flatpickr = $('#birth_date');
                flatpickr.length && flatpickr.each(function() {
                    var e = $(this);
                    e.flatpickr({
                        enableTime: false,
                        dateFormat: 'Y-m-d',
                        allowInput: false,
                        defaultDate: 'today',
                        altInput: true,
                        altFormat: 'j F, Y'
                    });
                });
            });
        </script>
    @endPushOnce
</x-layouts.auth>
