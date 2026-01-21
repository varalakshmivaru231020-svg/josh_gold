
document.addEventListener('DOMContentLoaded', function () {

    /* ==============================================
       PRELOADER
       ============================================== */
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', function () {
            setTimeout(function () {
                preloader.classList.add('hidden');
            }, 500);
        });
    }

    /* ==============================================
       HEADER SCRIPT
       ============================================== */
    const header = document.getElementById('mainHeader');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Back to Top Button
    const backToTopBtn = document.getElementById('backToTop');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });

    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /* ==============================================
       SWIPER SLIDERS
       ============================================== */

    // Hero Slider
    if (document.querySelector('.hero-swiper')) {
        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            effect: 'fade',
            speed: 1000,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }

    // Videos Slider
    if (document.querySelector('.videos-swiper')) {
        const videosSwiper = new Swiper('.videos-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.videos-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 3,
                }
            }
        });
    }

    /* ==============================================
       AOS (Animate On Scroll) LIKE FUNCTIONALITY
       ============================================== */
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // Only animate once
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.fade-up, .fade-in, .slide-left, .slide-right, .scale-in');
    animatedElements.forEach(el => {
        observer.observe(el);
    });

    // Animate stats numbers
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                const counters = document.querySelectorAll('.stat-number');
                counters.forEach(counter => {
                    counter.classList.add('counting');
                    // We could add a real count up function here if data-target is provided
                });
                statsObserver.unobserve(statsSection);
            }
        }, observerOptions);

        statsObserver.observe(statsSection);
    }
});
