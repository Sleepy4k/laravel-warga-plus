(() => {
  let e = document.querySelector(".layout-navbar"),
    t = document.getElementById("hero-animation"),
    r = document.querySelectorAll(".hero-dashboard-img"),
    o = document.querySelectorAll(".hero-elements-img");

  // Hero animation logic
  if ("1200" <= screen.width && t) {
    t.addEventListener("mousemove", function (event) {
      o.forEach((element) => {
        element.style.transform = "translateZ(1rem)";
      });
      r.forEach((element) => {
        var rotateY = (window.innerWidth - 2 * event.pageX) / 100;
        element.style.transform = `perspective(1200px) rotateX(${
          (window.innerHeight - 2 * event.pageY) / 100
        }deg) rotateY(${rotateY}deg) scale3d(1, 1, 1)`;
      });
    });

    e.addEventListener("mousemove", function (event) {
      o.forEach((element) => {
        element.style.transform = "translateZ(1rem)";
      });
      r.forEach((element) => {
        var rotateY = (window.innerWidth - 2 * event.pageX) / 100;
        element.style.transform = `perspective(1200px) rotateX(${
          (window.innerHeight - 2 * event.pageY) / 100
        }deg) rotateY(${rotateY}deg) scale3d(1, 1, 1)`;
      });
    });

    t.addEventListener("mouseout", function () {
      o.forEach((element) => {
        element.style.transform = "translateZ(0)";
      });
      r.forEach((element) => {
        element.style.transform =
          "perspective(1200px) scale(1) rotateX(0) rotateY(0)";
      });
    });
  }

  // Reviews swiper
  const reviewsSwiper = document.getElementById("swiper-reviews");
  if (reviewsSwiper) {
    new Swiper(reviewsSwiper, {
      slidesPerView: 1,
      spaceBetween: 5,
      grabCursor: !0,
      autoplay: {
        delay: 3e3,
        disableOnInteraction: !1,
      },
      loop: !0,
      loopAdditionalSlides: 1,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        1200: {
          slidesPerView: 3,
          spaceBetween: 26,
        },
        992: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
      },
    });

    // Navigation buttons
    const nextBtn = document.getElementById("reviews-next-btn");
    const prevBtn = document.getElementById("reviews-previous-btn");
    const swiperNext = document.querySelector(".swiper-button-next");
    const swiperPrev = document.querySelector(".swiper-button-prev");

    if (nextBtn && swiperNext) {
      nextBtn.addEventListener("click", function () {
        swiperNext.click();
      });
    }

    if (prevBtn && swiperPrev) {
      prevBtn.addEventListener("click", function () {
        swiperPrev.click();
      });
    }
  }

  // Clients logo swiper
  const clientsLogos = document.getElementById("swiper-clients-logos");
  if (clientsLogos) {
    new Swiper(clientsLogos, {
      slidesPerView: 2,
      autoplay: {
        delay: 3e3,
        disableOnInteraction: !1,
      },
      breakpoints: {
        992: {
          slidesPerView: 5,
        },
        768: {
          slidesPerView: 3,
        },
      },
    });
  }

  // Price toggler functionality
  document.addEventListener("DOMContentLoaded", function () {
    const priceToggler = document.querySelector(".price-duration-toggler");
    const monthlyPrices = [].slice.call(
      document.querySelectorAll(".price-monthly")
    );
    const yearlyPrices = [].slice.call(
      document.querySelectorAll(".price-yearly")
    );

    function togglePrice() {
      if (priceToggler && priceToggler.checked) {
        yearlyPrices.map(function (element) {
          element.classList.remove("d-none");
        });
        monthlyPrices.map(function (element) {
          element.classList.add("d-none");
        });
      } else if (priceToggler) {
        yearlyPrices.map(function (element) {
          element.classList.add("d-none");
        });
        monthlyPrices.map(function (element) {
          element.classList.remove("d-none");
        });
      }
    }

    togglePrice();

    if (priceToggler) {
      priceToggler.onchange = function () {
        togglePrice();
      };
    }
  });
})();
