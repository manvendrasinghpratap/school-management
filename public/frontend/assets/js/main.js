"use strict";
$(window).on("load", function () {
  $("#loading").fadeOut(500);
  accordian();
  aosActivation();
});

$(window).on("scroll", function () {
  counterInit();
  aosActivation();
  parallaxEffect();
});

$(document).ready(function () {
  // BANNER MODAL VIDEO
  $(".tl-testimonial-slider").owlCarousel({
    center: true,
    loop: true,
    margin: 60,
    autoplay: true,
    dots: false,
    nav: true,
    navText: [
      `<i class="fa-regular fa-arrow-left"></i>`,
      `<i class="fa-regular fa-arrow-right"></i>`,
    ],
    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 1.3,
        margin: 10,
      },

      576: {
        items: 1.4,
        margin: 20,
      },
      992: {
        items: 1.8,
        margin: 30,
      },
      1200: {
        items: 1.9,
        margin: 40,
      },
      1400: {
        margin: 60,
        items: 1.9,
      },
    },
  });

  //===============//
  const hamburgerBtn = document.querySelector(".tl-hamburger");
  if (hamburgerBtn) {
    hamburgerBtn.addEventListener("click", () => {
      document
        .querySelector(".kidba-menu-sidebar")
        .classList.add("sidebar-open");
    });
  }

  const sidebarCloseBtn = document.querySelector(".sidebar__close-btn");
  if (sidebarCloseBtn) {
    sidebarCloseBtn.addEventListener("click", () => {
      document
        .querySelector(".kidba-menu-sidebar")
        .classList.remove("sidebar-open");
    });
  }
  //===============//

  //
  $(".tl-nav-menu").meanmenu({
    meanMenuContainer: ".mobile-menu",
    meanScreenWidth: 991,
    meanExpand: ["+"],
    meanClose: ["-"],
  });

  //===================== DARK LIGHT TOGGLER
  $(".tl-dark-light-view-box").on("click", function () {
    $("body").toggleClass("dark_mode");
    $(".tl-dark-light-view-box").toggleClass("has-clicked");
    $(".tl-dark-light-view-toogle-dot").toggleClass("pos-bottom");
  });

  // ============= INDEX-5 BANNER SLIDER JS START ===============
  $(".tl-5-banner-slider").owlCarousel({
    items: 1,
    loop: true,
    navText: [
      `<img src="assets/images/tl-5/banner-slider-arrow.png" alt="arrow">`,
      `<img src="assets/images/tl-5/banner-slider-arrow-2.png" alt="arrow">`,
    ],
    dots: false,
    nav: true,
    autoplay: true,

    responsive: {
      0: {
        nav: false,
      },

      576: {
        nav: true,
      },
    },
  });
  // ============= INDEX-5 BANNER SLIDER JS END ===============

  // ============= INDEX-5 FACULTIES SLIDER JS START ===============
  $(".tl-5-faculties-slider").owlCarousel({
    items: 4,
    loop: true,
    margin: 20,
    dots: true,
    autoplay: true,

    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 2,
      },

      768: {
        items: 3,
      },

      992: {
        items: 4,
        margin: 30,
      },
    },
  });
  // ============= INDEX-5 FACULTIES SLIDER JS END ===============

  // ============= INDEX-5 TESTIMONIAL SLIDER JS START ===============
  $(".tl-5-testimonial-img-slider").slick({
    slidesToShow: 1,
    arrows: false,
    swipe: false,
    asNavFor: ".tl-5-testimonial-slider",
    fade: true,
    autoplay: true,
  });

  $(".tl-5-testimonial-slider").slick({
    slidesToShow: 1,
    asNavFor: ".tl-5-testimonial-img-slider",
    appendArrows: $(".tl-5-testimonial-slider-nav"),
    prevArrow:
      '<button type="button" class="slick-prev"><i class="fa-light fa-angle-left"></i></button>',
    nextArrow:
      '<button type="button" class="slick-next"><i class="fa-light fa-angle-right"></i></button>',
    autoplay: true,
  });

  // STICKY HEADER
  window.addEventListener("scroll", () => {
    const toFixHeaders = document.querySelectorAll(".to-be-fixed");
    toFixHeaders.forEach((toFixHeader) => {
      if (window.scrollY > 100) {
        toFixHeader.classList.add("fixed");
        document.body.style.paddingTop =
          toFixHeader.getBoundingClientRect().height + "px";
      } else {
        toFixHeader.classList.remove("fixed");
        document.body.style.paddingTop = 0;
      }
    });
  });

  // ============= INDEX-5 TESTIMONIAL SLIDER JS END ===============

  if (document.querySelector(".tl-11-pop-articles-col")) {
    mixitup(".tl-11-pop-articles-col");
  }

  $(".tl-6-members").owlCarousel({
    items: 5,
    loop: true,
    autoplay: true,
    dots: false,
    nav: false,
    navText: [
      `<i class="fa-regular fa-arrow-left"></i>`,
      `<i class="fa-regular fa-arrow-right"></i>`,
    ],

    responsive: {
      0: {
        items: 3,
      },

      480: {
        items: 4,
      },

      992: {
        nav: true,
      },
    },
  });

  document.querySelectorAll(".tl-4-social").forEach((eachSocial) => {
    $(eachSocial).hover(function () {
      $(this).find(".tl-4-social-hidden-content").slideDown("medium").show();
    });
    $(eachSocial).mouseleave(function () {
      $(this).find(".tl-4-social-hidden-content").slideUp("medium");
    });
  });

  // INDEX-7 BANNER SLIDER
  $(".tl-7-banner-slider").owlCarousel({
    items: 1,
    animateIn: "fadeIn",
    animateOut: "fadeOut",
    dotsContainer: ".tl-7-banner-slider-dots",
    loop: true,
    autoPlay: true,
  });

  // ------------------ INDEX-7 TICKER TEXT
  $(".tl-7-marquee-line").eocjsNewsticker({
    divider: "",
    speed: 10,
  });

  // ------------------ INDEX-7 FACULTY SLIDER
  $(".tl-7-faculty-row").owlCarousel({
    items: 1,
    loop: true,
    autoplay: false,
    margin: 30,
    dots: true,
    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 2,
      },

      768: {
        items: 3,
      },

      992: {
        items: 3,
        margin: 30,
      },
    },
  });

  // INDEX-7 & 2 SEARCH
  const kb_11_searchBtn = document.querySelector(".kb-searh-open-btn-11");

  if (kb_11_searchBtn) {
    kb_11_searchBtn.addEventListener("click", () => {
      document
        .querySelector(".tl-7-search-form-modal")
        .classList.toggle("show");
    });
  }

  // INDEX-7 ABOUT VERTICAL SLIDER
  $(".tl-7-about-info-cards-col-1").bxSlider({
    auto: true,
    mode: "vertical",
    ticker: true,
    minSlides: 2,
    speed: 5000,
    tickerHover: true,
  });

  $(".tl-7-about-info-cards-col-2").bxSlider({
    autoDirection: "prev",
    auto: true,
    mode: "vertical",
    ticker: true,
    minSlides: 2,
    speed: 5000,
    tickerHover: true,
    adaptiveHeight: true,
  });

  //

  // INDEX-8 BANNER SLIDER
  $(".tl-8-banner-slider").owlCarousel({
    items: 1,
    loop: true,
    dots: false,
    autoplay: true,
    navContainer: ".tl-8-banner-slider-nav",
    navText: [
      `<i class="fa-regular fa-arrow-left"></i>`,
      ` <i class="fa-regular fa-arrow-right"></i>`,
    ],
  });

  // INDEX-8 COURSES SLIDER
  $(".tl-8-courses-slider").owlCarousel({
    items: 1,
    dots: false,
    navContainer: ".tl-8-courses-slider-nav",
    navText: [
      `<i class="fa-regular fa-arrow-left"></i>`,
      ` <i class="fa-regular fa-arrow-right"></i>`,
    ],
    loop: true,
    autoplay: true,
  });

  // INDEX-8 TESTIMONIAL SLIDER
  $(".tl-8-testimonial-slider").owlCarousel({
    items: 1,
    autoplay: true,
    dots: false,
    loop: true,
    navContainer: ".tl-8-testimonial-slider-nav",
    navText: [
      `<i class="fa-regular fa-arrow-left"></i>`,
      ` <i class="fa-regular fa-arrow-right"></i>`,
    ],
  });

  // INDEX-12 ACCORDION
  const accordionItems = document.querySelectorAll(".tl-8-accordion-item");
  accordionItems.forEach((accordionItem) => {
    accordionItem.onclick = () => {
      const openedItems = document.querySelector(".tl-8-accordion-item.open");
      openedItems.classList.remove("open");
      accordionItem.classList.add("open");
    };
  });

  // INDEX-12 COURSES SLIDER
  var swiper1 = new Swiper("#tl-12-courses-slider", {
    spaceBetween: 30,
    slidesPerView: 3,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        spaceBetween: 20,
      },
    },
    pagination: {
      el: "#tl-12-courses-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: "#tl-12-courses-slider-next",
      prevEl: "#tl-12-courses-slider-prev",
    },
  });

  // INDEX-12 TESTIMONIAL SLIDER
  const progressCircle = document.querySelector(".autoplay-progress svg");
  const progressContent = document.querySelector(".autoplay-progress span");

  if ((progressContent, progressCircle)) {
    var swiper = new Swiper(".tl-12-testimonial-slider", {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".tl-12-testimonial-slider-pagination",
        type: "fraction",
      },
      navigation: {
        nexEl: ".tl-12-testimonial-slider-next",
        prevEl: ".tl-12-testimonial-slider-prev",
      },
      on: {
        autoplayTimeLeft(s, time, progress) {
          progressCircle.style.setProperty("--progress", 1 - progress);
        },
        autoplayResume(swiper) {
          progressContent.innerHTML = `<i class="fa-solid fa-sharp fa-pause"></i>`;
        },
      },
    });
    if (!swiper.paused) {
      progressContent.innerHTML = `<i class="fa-solid fa-sharp fa-pause"></i>`;
    } else {
      progressContent.innerHTML = `<i class="fa-solid fa-sharp fa-play"></i>`;
    }

    progressCircle.addEventListener("click", () => {
      if (!swiper.autoplay.paused) {
        swiper.autoplay.pause();
        progressContent.innerHTML = `<i class="fa-solid fa-sharp fa-play"></i>`;
      } else {
        swiper.autoplay.resume();
        progressContent.innerHTML = `<i class="fa-solid fa-sharp fa-pause"></i>`;
      }
    });
  }

  // INDEX-12 MENTOR SLIDER
  var swiper2 = new Swiper("#tl-12-mentor-slider", {
    slidesPerView: 3,
    spaceBetween: 30,
    pagination: {
      el: "#tl-12-mentor-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nexEl: "#tl-12-mentor-slider-next",
      prevEl: "#tl-12-mentor-slider-prev",
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      480: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
    },
  });

  // data - background
  $("[data-background]").each(function () {
    $(this).css(
      "background-image",
      "url(" + $(this).attr("data-background") + ")"
    );
  });

  $("[data-bg-color]").each(function () {
    $(this).css("background-color", $(this).attr("data-bg-color"));
  });

  // INDEX-12
  $(".tl-12-blogs-slider").slick({
    vertical: true,
    slidesToShow: 3,
    appendArrows: $(".tl-12-blogs-slider-nav"),
    prevArrow:
      '<button type="button" class="slick-prev"><i class="fa-light fa-arrow-up"></i></button>',
    nextArrow:
      '<button type="button" class="slick-next"><i class="fa-light fa-arrow-down"></i></button>',
    autoplay: true,
    responsive: [
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });

  // INDEX-9 CLASSES SLIDER
  $(".tl-9-classes-slider").owlCarousel({
    items: 3,
    navText: [
      `<i class="fa-regular fa-arrow-left"></i>`,
      ` <i class="fa-regular fa-arrow-right"></i>`,
    ],
    autoplay: true,
    loop: true,
    dots: false,

    responsive: {
      0: {
        margin: 20,
        nav: false,
        items: 1,
      },

      768: {
        margin: 24,
        items: 2,
      },

      992: {
        items: 3,
        margin: 24,
      },

      1200: {
        nav: true,
        margin: 30,
      },
    },
  });

  // INEDX 11 & 14 TESTIMONIAL SLIDER
  $(".tl-7-reviewer-img-slider").slick({
    arrows: false,
    fade: true,
    draggable: false,
    asNavFor: ".tl-7-testimonial-slider",
  });

  $(".tl-7-testimonial-slider").slick({
    arrows: false,
    asNavFor: ".tl-7-reviewer-img-slider",
    autoplay: true,
    dots: true,
    appendDots: $(".tl-7-testimonial-slider-dots"),
  });

  // INDEX-9 EVENTS TIMELINE SLIDER
  $(".tl-9-events-slider-timeline").owlCarousel({
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
  });

  const timelineSteps = document.querySelectorAll(".timeline-step");
  timelineSteps.forEach((timelineStep) => {
    timelineStep.addEventListener("click", (e) => {
      timelineSteps.forEach((step) => {
        step.classList.remove("clicked");
      });

      e.currentTarget.classList.add("clicked");
    });
  });

  // INDEX-9 EVENTS CONTENT SLIDER
  $(".tl-9-events-slider-content").owlCarousel({
    // items: 3,
    // margin: 30,
    dots: false,
    dotsEach: 1,
    URLhashListener: true,
    startPosition: "#event2",
    center: true,
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
    nav: true,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: ".tl-9-events-slider-nav",

    responsive: {
      0: {
        margin: 10,
        items: 1,
      },

      480: {
        items: 1.4,
        margin: 15,
      },

      768: {
        margin: 20,
        items: 2,
      },

      992: {
        items: 2.5,
        margin: 25,
      },

      1200: {
        margin: 30,
        items: 3,
      },
    },
  });

  // INDEX-4 BANNER SLIDER
  $(".tl-4-banner-slider").owlCarousel({
    items: 1,
    margin: 0,
    loop: true,
    dots: true,
    dotsContainer: ".tl-4-banner-slider-dots",
    autoplay: true,
  });

  // INDEX-4 TESTIMONIAL SLIDER
  $(".tl-4-testimonial-slider").owlCarousel({
    items: 1,
    loop: true,
    nav: false,
    autoplay: true,
    dotsContainer: ".tl-4-testimonial-users",
  });

  // INDEX-13 COUNTRIES SLIDER
  $(".tl-13-countries-row").bxSlider({
    minSlides: 6,
    maxSlides: 6,
    slideWidth: 600,
    ticker: true,
    speed: 25000,
    tickerHover: true,
    responsive: true,
  });

  // ------------------ INDEX-13 FEATURES SLIDER
  $(".tl-13-about-features").eocjsNewsticker({
    divider: "",
    speed: 10,
  });

  // ------------------ INDEX-13 TESTIMONY SLIDER
  $(".tl-13-testimonial-user-slider").slick({
    arrows: false,
    asNavFor: ".tl-13-testimonial-slider",
    fade: true,
    draggable: false,
  });

  // INDEX-13 TESTIMONIAL SLIDER
  $(".tl-13-testimonial-slider").slick({
    arrows: false,
    asNavFor: ".tl-13-testimonial-user-slider",
    autoplay: true,
  });

  // INDEX-13 COURSE SLIDER
  $(".tl-13-courses-slider").owlCarousel({
    margin: 30,
    loop: true,
    dots: false,
    nav: true,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: ".tl-13-courses-slider-nav",
    autoplay: true,

    responsive: {
      0: {
        margin: 20,
        items: 1,
      },
      576: {
        items: 1.3,
        center: true,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
        margin: 25,
      },
      1200: {
        margin: 25,
      },
      1400: {
        margin: 30,
      },
    },
  });

  // INDEX-13 BLOGS SLIDER
  $(".tl-13-blogs-slider").owlCarousel({
    margin: 30,
    loop: true,
    dots: false,
    nav: true,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: ".tl-13-blogs-slider-nav",
    autoplay: true,

    responsive: {
      0: {
        margin: 20,
        items: 1,
      },
      576: {
        items: 1.3,
        center: true,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
        margin: 25,
      },
      1200: {
        margin: 25,
      },
      1400: {
        margin: 30,
      },
    },
  });

  // INDEX-2 BANNER SLIDER
  $(".tl-2-banner-slider").owlCarousel({
    items: 1,
    loop: true,
    autoplay: true,
    dots: false,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-2-banner-slider-nav",
    autoplayTimeout: 7000,
    autoplaySpeed: 1700,
  });

  // INDEX-2 SERVICE SLIDER
  $(".tl-2-services-slider").owlCarousel({
    items: 3,
    margin: 30,
    loop: true,
    autoplay: true,
    dots: false,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-2-services-slider-nav",

    responsive: {
      0: {
        items: 1,
        margin: 20,
      },

      480: {
        items: 1.5,
        center: true,
      },

      768: {
        items: 2,
        margin: 25,
      },

      992: {
        items: 3,
        margin: 25,
      },

      1200: {
        margin: 30,
      },
    },
  });

  // INDEX-2 COURESE SLIDER
  $(".tl-2-courses-row").owlCarousel({
    items: 3,
    margin: 30,
    loop: true,
    autoplay: false,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-2-courses-slider-nav",
    dots: false,

    responsive: {
      0: {
        items: 1,
        margin: 20,
      },

      480: {
        items: 1.5,
        center: true,
      },

      768: {
        items: 2,
        margin: 25,
      },

      992: {
        items: 3,
        margin: 25,
      },

      1200: {
        margin: 30,
        items: 3,
      },
    },
  });

  // INDEX-2 BLOGS SLIDER
  $(".tl-2-blogs-row").owlCarousel({
    items: 3,
    margin: 30,
    loop: true,
    autoplay: true,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-2-blogs-slider-nav",
    dots: false,

    responsive: {
      0: {
        items: 1,
        margin: 20,
      },

      480: {
        items: 1.5,
        center: true,
      },

      768: {
        items: 2,
        margin: 25,
      },

      992: {
        items: 3,
        margin: 25,
      },

      1200: {
        margin: 30,
      },
    },
  });

  // INDEX-2 TEACHER HOVER
  function setParentHeight() {
    let totalHeight = 0;

    $(".tl-2-teacher-info").each(function () {
      totalHeight = $(this).outerHeight();
    });

    $(".tl-2-teacher-txt").height(totalHeight);
  }
  $(window).on("load resize", setParentHeight());

  // INDEX-6 BANNER SLIDER
  let slideFullWidth = 0;
  const banner18Slider = new Swiper(".tl-6-banner-slider", {
    spaceBetween: 30,
    slidesPerView: "auto",
    autoplay: true,

    breakpoints: {
      0: {
        slidesPerView: 1,
        slideActiveClass: "swiper-slide-next",
        slideNextClass: "swiper-slide-active",
      },

      992: {
        slidesPerView: "auto",
      },

      1200: {
        spaceBetween: 30,
      },
    },
  });

  //
  $(".tl-6-news-slider").owlCarousel({
    dots: false,
    margin: 60,
    navContainer: "#tl-6-news-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      `<i class="fa-light fa-arrow-right"></i>`,
    ],
    loop: true,

    responsive: {
      0: {
        margin: 10,
        items: 1,
      },

      576: {
        margin: 30,
        items: 1,
      },

      768: {
        margin: 30,
        items: 1,
        center: true,
      },

      992: {
        margin: 30,
        items: 2,
      },

      1200: {
        items: 3,
        margin: 30,
      },
      1400: {
        margin: 60,
      },
    },
  });

  // INDEX-1 MEMBERS SLIDER
  $(".tl-1-members").bxSlider({
    minSlides: 5,
    maxSlides: 5,
    slideWidth: 200,
    slideMargin: 0,
    ticker: true,
    speed: 15000,
    tickerHover: true,
  });

  // INDEX-1 BLOG SLIDER
  $(".tl-1-courses-slider").owlCarousel({
    navContainer: "#tl-1-courses-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      `<i class="fa-light fa-arrow-right"></i>`,
    ],
    dots: false,
    margin: 30,
    loop: true,

    responsive: {
      0: {
        margin: 10,
        items: 1,
      },

      480: {
        items: 1.5,
        margin: 15,
        center: true,
      },

      576: {
        margin: 15,
        items: 2,
      },

      768: {
        margin: 20,
        items: 2,
      },

      992: {
        margin: 20,
        items: 2,
      },
      1200: {
        margin: 30,
        items: 3,
      },
    },
  });

  // INDEX-1 TESTIMONIAL SLIDER
  $(".tl-1-testimonial-slider").owlCarousel({
    nav: false,
    dots: true,
    items: 1,
    loop: true,
    autoplay: true,
  });

  // INDEX-1 SUB-BANNER TICKER
  $("#tl-1-sub-banner-ticker").bxSlider({
    minSlides: 1,
    maxSlides: 1,
    slideMargin: 0,
    ticker: true,
    speed: 25000,
  });

  // INDEX-1 SUB-BANNER TICKER 2
  $(".tl-1-sub-banner-ticker-2").bxSlider({
    minSlides: 1,
    maxSlides: 1,
    slideMargin: 0,
    ticker: true,
    speed: 25000,
    autoDirection: "prev",
  });

  // INDEX-1 TEACHERS SLIDER
  $(".tl-1-teachers-slider").owlCarousel({
    loop: true,
    autoplay: true,
    dots: true,
    dotsContainer: "#tl-1-teachers-slider-dots",

    responsive: {
      0: {
        items: 1,
        margin: 10,
      },
      480: {
        items: 2,
        margin: 10,
      },
      576: {
        margin: 20,
        items: 2,
      },
      768: {
        items: 2,
        margin: 20,
      },
      992: {
        margin: 20,
        items: 3,
      },
      1200: {
        margin: 30,
      },
    },
  });

  // INDEX-14 BANNER SLIDER
  $(".tl-14-banner-slider").owlCarousel({
    items: 1,
    dots: false,
    autoplay: true,
    loop: true,
    animateIn: "fadeIn",
    animateOut: "fadeOut",
    navContainer: "#tl-14-banner-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      `<i class="fa-light fa-arrow-right"></i>`,
    ],
  });

  // INDEX-14 COURSE SLIDER
  $(".tl-14-courses-row").owlCarousel({
    margin: 30,
    loop: true,
    autoplay: true,
    nav: false,
    dots: true,
    dotsContainer: "#tl-14-courses-slider-dots",
    responsive: {
      0: {
        items: 1,
        margin: 20,
      },

      480: {
        items: 1.3,
        margin: 20,
        center: true,
      },

      576: {
        items: 1.5,
        margin: 20,
        center: true,
      },

      768: {
        items: 2,
        margin: 20,
      },
      1200: {
        items: 3,
        margin: 20,
      },
      1400: {
        margin: 30,
      },
    },
  });

  $(".tl-14-software-slider").owlCarousel({
    loop: true,
    autoplay: true,
    items: 1,
    navContainer: "#tl-14-software-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      `<i class="fa-light fa-arrow-right"></i>`,
    ],
    dotsContainer: ".tl-14-software-slider-dots",
  });

  $(".tl-14-testimonial-slider").slick({
    asNavFor: ".tl-14-testimonial-users",
    arrows: false,
    autoplay: true,
    autoplaySpeed: 3000,
  });

  $(".tl-14-testimonial-users").slick({
    arrows: false,
    asNavFor: ".tl-14-testimonial-slider",
    slidesToShow: 3,
    focusOnSelect: true,
    centerMode: true,
    centerPadding: "0",

    responsive: [
      {
        breakpoint: 480,
        settings: {
          // centerMode: false,
          slidesToShow: 1,
        },
      },
    ],
  });

  // Event Details Page Timer
  $("#tl-event-timer").syotimer({
    date: new Date(2023, 9, 2, 20, 30),
    periodic: true,
  });

  // 15 ========================
  // INDEX-15 FEATURES SLIDER
  $(".tl-15-about-features").eocjsNewsticker({
    divider: "",
    speed: 10,
  });

  // INDEX-15 BANNER SLIDER
  $(".tl-15-banner-slider").owlCarousel({
    autoPlay: true,
    loop: true,
    nav: true,
    dots: true,
    items: 1,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-15-banner-slider-nav",
    speed: 2000,
    animateOut: "tl-15-banner-fade-out",
    animateIn: "tl-15-banner-fade-in",
  });

  // INDEX-15 COURSE SLIDER
  $(".tl-15-courses-row").owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    items: 1,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-15-courses-slider-nav",
  });

  // INDEX-15 COURSE SLIDER
  $(".tl-15-courses-type-1").owlCarousel({
    loop: true,
    autoplay: true,
    nav: false,
    dots: true,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-15-courses-slider-nav-type-1",
    responsive: {
      1400: {
        margin: 30,
        items: 4,
      },

      991: {
        items: 3,
        margin: 20,
      },

      767: {
        items: 2,
        margin: 20,
      },

      576: {
        items: 1.5,
        margin: 20,
        center: true,
      },

      0: {
        items: 1,
      },
    },
  });

  // INDEX-15 TESTIMONIAL SLIDER
  $(".tl-15-testimonial-slider").owlCarousel({
    items: 1,
    loop: true,
    autoplay: true,
    dotsContainer: ".tl-15-testimonial-users",
  });

  // INDEX-15 BLOG SLIDER
  $(".tl-15-blog-slider").owlCarousel({
    loop: true,
    margin: 30,
    items: 2,
    dots: true,
    responsive: {
      // 1400: {
      //     margin: 30,
      //     items: 4,
      // },

      // 991: {
      //     items: 3,
      //     margin: 20,
      // },

      767: {
        items: 2,
      },

      576: {
        items: 1,
      },

      0: {
        items: 1,
      },
    },
  });

  // INDEX-15 MEMBER SLIDER
  $(".tl-15-member-slider").owlCarousel({
    loop: true,
    margin: 30,
    items: 3,
    dots: true,
    responsive: {
      1400: {
        margin: 30,
        items: 3,
      },

      991: {
        items: 3,
        margin: 20,
      },

      767: {
        items: 2,
      },

      576: {
        items: 1,
      },

      0: {
        items: 1,
      },
    },
  });
  // INDEX-15 INSTAGRAM SLIDER
  $(".tl-15-instagram-slider").owlCarousel({
    loop: true,
    items: 5,
    dots: true,
    autoPlay: true,
    autoplayHoverPause: true,
    autoplayTimeout: 3000,
    responsive: {
      1400: {
        items: 5,
      },

      991: {
        items: 4,
      },

      767: {
        items: 3,
      },

      576: {
        items: 2,
      },

      0: {
        items: 1,
        autoplayTimeout: 1000,
      },
    },
  });

  // INDEX 16 BANNER / HERO SLIDER
  var textSlider = $(".tl-16-banner-txt-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    speed: 500,
    arrows: false,
    fade: true,
    autoplay: true,
    autoplaySpeed: 5000,
    dots: true,
    appendDots: $(".tl-16-banner-slider-dots"),
  });

  var imageSlider = $(".tl-16-banner-img-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    speed: 800,
    arrows: false,
    fade: true,
    autoplay: true,
    autoplaySpeed: 5000,
  });

  textSlider.on(
    "beforeChange",
    function (event, slick, currentSlide, nextSlide) {
      imageSlider.slick("slickGoTo", nextSlide);
    }
  );

  imageSlider.on(
    "beforeChange",
    function (event, slick, currentSlide, nextSlide) {
      textSlider.slick("slickGoTo", nextSlide);
    }
  );

  // INDEX-16 TOP COURSES SLIDER JS START
  $(".tl-16-course-top-slider").owlCarousel({
    dots: false,
    margin: 30,
    navContainer: "#tl-16-course-top-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      `<i class="fa-light fa-arrow-right"></i>`,
    ],
    loop: true,
    items: 5,
    autoplay: true,
    responsive: {
      0: {
        items: 1,
      },

      576: {
        items: 2,
      },

      991: {
        items: 3,
      },

      1199: {
        items: 4,
      },

      1399: {
        items: 5,
      },
    },
  });

  // INDEX-16 ONGOING COURSES SLIDER JS START
  $(".tl-16-course-slider").owlCarousel({
    items: 3,
    loop: true,
    margin: 30,
    dots: true,
    autoplay: true,
    navContainer: "#tl-courses-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 1,
      },

      767: {
        items: 2,
      },

      1199: {
        items: 3,
      },
    },
  });

  // INDEX-16 FEATURE COURSES SLIDER JS START
  $(".tl-16-course-slider-1").owlCarousel({
    items: 3,
    loop: true,
    margin: 30,
    dots: true,
    autoplay: true,
    navContainer: "#tl-feature-classes-slider-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 1,
      },

      767: {
        items: 2,
      },

      1199: {
        items: 3,
      },
    },
  });

  //17==================================

  // INDEX-17 POPULAR COURSES SLIDER
  var swiper3 = new Swiper("#tl-17-courses-slider", {
    spaceBetween: 30,
    slidesPerView: 3,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        spaceBetween: 20,
      },
    },
    pagination: {
      el: "#tl-17-courses-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: "#tl-17-courses-slider-next",
      prevEl: "#tl-17-courses-slider-prev",
    },
  });

  // INDEX-17 NEW-RELEASE COURSES SLIDER
  var swiper4 = new Swiper("#tl-17-new-release-courses-slider", {
    spaceBetween: 30,
    slidesPerView: 3,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        spaceBetween: 20,
      },
    },
    pagination: {
      el: "#tl-17-new-release-courses-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: "#tl-17-new-release-courses-slider-next",
      prevEl: "#tl-17-new-release-courses-slider-prev",
    },
  });

  // INDEX-17 POPULAR COURSES SLIDER
  var swiper5 = new Swiper("#tl-17-popular-courses-slider", {
    spaceBetween: 30,
    slidesPerView: 3,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        spaceBetween: 20,
      },
    },
    pagination: {
      el: "#tl-17-popular-courses-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: "#tl-17-popular-courses-slider-next",
      prevEl: "#tl-17-popular-courses-slider-prev",
    },
  });

  // INDEX-17 POPULAR COURSES SLIDER
  var swiper6 = new Swiper("#tl-17-blog-slider", {
    spaceBetween: 30,
    slidesPerView: 3,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        spaceBetween: 20,
      },
    },
    pagination: {
      el: "#tl-17-blog-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: "#tl-17-blog-slider-next",
      prevEl: "#tl-17-blog-slider-prev",
    },
  });

  // INDEX-17 POPULAR COURSES SLIDER
  var swiper7 = new Swiper("#tl-17-category-slider", {
    spaceBetween: 30,
    slidesPerView: 1,
    pagination: {
      el: "#tl-17-category-slider-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: "#tl-17-category-slider-next",
      prevEl: "#tl-17-category-slider-prev",
    },
  });

  // INDEX-17 TESTIMONIAL SLIDER
  const progressCircle2 = document.querySelector(
    ".autoplay-progress-2 svg circle"
  );
  const progressContent2 = document.querySelector(".autoplay-progress-2 span");

  if ((progressContent2, progressCircle2)) {
    var swiper = new Swiper("#tl-17-testimonial-slider", {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      navContainer: "",
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: "#tl-17-testimonial-slider-pagination",
        type: "fraction",
      },
      navigation: {
        nextEl: "#tl-17-testimonial-slider-next",
        prevEl: "#tl-17-testimonial-slider-prev",
      },
      on: {
        autoplayTimeLeft(s, time, progress) {
          progressCircle.style.setProperty("--progress", 1 - progress);
        },
        autoplayResume(swiper) {
          progressContent.innerHTML = `<i class="fa-solid fa-sharp fa-pause"></i>`;
        },
      },
    });
    if (!swiper.paused) {
      progressContent2.innerHTML = `<i class="fa-solid fa-sharp fa-pause"></i>`;
    } else {
      progressContent2.innerHTML = `<i class="fa-solid fa-sharp fa-play"></i>`;
    }

    progressCircle2.addEventListener("click", () => {
      if (!swiper.autoplay.paused) {
        swiper.autoplay.pause();
        progressContent2.innerHTML = `<i class="fa-solid fa-sharp fa-play"></i>`;
      } else {
        swiper.autoplay.resume();
        progressContent2.innerHTML = `<i class="fa-solid fa-sharp fa-pause"></i>`;
      }
    });
  }

  // 18
  if (document.querySelector(".tl-class-filtering")) {
    mixitup(".tl-class-filtering");
  }
  $(".tl-18-blog-slider").owlCarousel({
    items: 3,
    loop: true,
    margin: 30,
    dots: true,
    autoplay: true,
    navContainer: ".tl-18-blog-container-nav",
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 1,
      },

      767: {
        items: 2,
      },

      1199: {
        items: 3,
      },
    },
  });

  //19
  // INDEX-19 BANNER SLIDER
  $(".tl-19-banner-slider").owlCarousel({
    items: 1,
    animateIn: "fadeIn",
    animateOut: "fadeOut",
    dotsContainer: ".tl-19-banner-slider-dots",
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
  });

  // INDEX-19 COURSE SLIDER
  $(".tl-19-courses-row").owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    items: 1,
    autoplay: true,
    autoplayTimeout: 5000,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-19-courses-slider-nav",
  });

  // INDEX-19 COURSE SLIDER
  $(".tl-19-courses-slider-2").owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    items: 4,
    margin: 24,
    autoplay: true,
    autoplayTimeout: 3000,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-19-courses-slider-nav-2",
    responsive: {
      1400: {
        items: 4,
      },

      991: {
        items: 3,
        margin: 20,
      },

      767: {
        items: 2,
        margin: 20,
      },

      576: {
        items: 1,
        margin: 20,
      },

      0: {
        items: 1,
      },
    },
  });

  // INDEX-19 COURSE SLIDER
  $(".tl-19-expert-slider").owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    items: 3,
    margin: 24,
    autoplay: true,
    autoplayTimeout: 4000,
    navText: [
      `<i class="fa-light fa-arrow-left"></i>`,
      ` <i class="fa-light fa-arrow-right"></i>`,
    ],
    navContainer: "#tl-19-expert-slider-nav",
    responsive: {
      1400: {
        items: 3,
      },

      991: {
        items: 3,
        margin: 20,
      },

      767: {
        items: 2,
        margin: 20,
      },

      576: {
        items: 1,
        margin: 20,
      },

      0: {
        items: 1,
      },
    },
  });

  // INEDX 11 & 14 TESTIMONIAL SLIDER
  $(".tl-19-testimony-img-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: ".tl-19-testimony-slider",
  });

  $(".tl-19-testimony-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: ".tl-19-testimony-img-slider",
    dots: false,
    centerMode: false,
    arrows: true,
    centerPadding: "60px",
    prevArrow: `<button type="button" class="slick-prev"><i class="fa-light fa-arrow-left"></i></button>`,
    nextArrow: `<button type="button" class="slick-next"><i class="fa-light fa-arrow-right"></i></button>`,
    appendArrows: ".tl-19-testimony-slider-navs",
  });

  /*---------------------------
  Cart List Open & Close
  ---------------------------*/
  $(document).on("click", function (event) {
    if (!$(event.target).closest(".cart-list").length) {
      $("body").removeClass("clear");
      $("#headerCartWrap").removeClass("active");
    }
  });

  $(".cart-close").on("click", function () {
    $("body").removeClass("clear");
    $("#headerCartWrap").removeClass("active");
  });

  $(".tl-header-cart-btn").on("click", function (event) {
    event.stopPropagation();
    $("body").addClass("clear");
    $("#headerCartWrap").addClass("active");
  });

  /*---------------------------
  Wish List Open & Close
  ---------------------------*/
  $(".tl-header-wishlist-btn").on("click", function (event) {
    event.stopPropagation();
    $("body").addClass("clear");
    $("#headerCartWrap").addClass("active");
  });

  $(".wish-close").on("click", function () {
    $("body").removeClass("clear");
    $("#headerCartWrap").removeClass("active");
  });

  /*---------------------------
  Search Open & Close
  ---------------------------*/
  $(".tl-header-search-btn").on("click", function () {
    $("body").addClass("clear");
    $("#headerSearchWrap").addClass("active");
  });
  $(".search-close").on("click", function () {
    $("body").removeClass("clear");
    $("#headerSearchWrap").removeClass("active");
  });
});

// Functional elements activation
function counterInit() {
  if ($(".odometer")) {
    function winScrollPosition() {
      var scrollPos = $(window).scrollTop(),
        winHeight = $(window).height();
      var scrollPosition = Math.round(scrollPos + winHeight / 1.2);
      return scrollPosition;
    }

    $(".odometer").each(function () {
      var elemOffset = $(this).offset().top;
      if (elemOffset < winScrollPosition()) {
        $(this).html($(this).data("count-to"));
      }
    });
  }
}

function accordian() {
  $(".tl-accordian").children(".tl-accordian-body").hide();
  $(".tl-accordian.active").children(".tl-accordian-body").show();
  $(".tl-accordian-head").on("click", function () {
    $(this)
      .parent(".tl-accordian")
      .siblings()
      .children(".tl-accordian-body")
      .slideUp(250);
    $(this).siblings().slideDown(250);
    $(this)
      .parent()
      .parent()
      .siblings()
      .find(".tl-accordian-body")
      .slideUp(250);
    /* Accordian Active Class */
    $(this).parents(".tl-accordian").addClass("active");
    $(this).parent(".tl-accordian").siblings().removeClass("active");
  });
}

function parallaxEffect() {
  $(".tl-parallax").each(function () {
    var windowScroll = $(document).scrollTop(),
      windowHeight = $(window).height(),
      barOffset = $(this).offset().top,
      barHeight = $(this).height(),
      barScrollUp = barOffset <= windowScroll + windowHeight,
      barScrollDown = barOffset + barHeight >= windowScroll;

    if (barScrollDown && barScrollUp) {
      var miniEffectPixel = windowScroll / 1;
      var miniEffectRotate = windowScroll / 10;
      var miniLightEffectPixel = windowScroll / 10;

      $(this)
        .find(".tl-to-left")
        .css({
          transform: `translateX(-${miniEffectPixel}px)`,
          transition: ".6s",
        });
      $(this)
        .find(".tl-to-right")
        .css({
          transform: `translateX(${miniEffectPixel}px)`,
          transition: ".6s",
        });
      $(this)
        .find(".tl-to-right-light")
        .css({
          transform: `translateX(${miniLightEffectPixel}px)`,
          transition: ".6s",
        });
      $(this)
        .find(".tl-to-rotate")
        .css({
          transform: `rotate(${miniEffectRotate}deg)`,
          transition: ".6s",
        });
    }
  });
}

function aosActivation() {
  if (typeof AOS !== "undefined") {
    AOS.init({
      duration: 1000,
      easing: "ease-in-out",
      once: false,
      offset: 200,
    });
    setTimeout(() => {
      AOS.refreshHard();
    }, 500);
  } else {
    console.error("AOS library not found.");
  }
  // AOS.init({
  //   duration: 1000,
  //   easing: "ease-in-out",
  //   once: false,
  //   offset: 200,
  // });

  // setTimeout(() => {
  //   AOS.refreshHard();
  // }, 500);
}
