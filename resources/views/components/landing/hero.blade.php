<section id="landingHero" class="section-py landing-hero position-relative">
    <img src="{{ asset('img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
        class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100" loading="lazy" />
    <div class="container">
        {{ $slot }}
    </div>
</section>
