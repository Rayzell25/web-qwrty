/* =====================================================================
   RAYZELL STORES — interaksi UI
   - Reveal on scroll (IntersectionObserver)
   - Navbar shadow saat scroll
   - Scroll progress bar
   - Tombol back-to-top
   ===================================================================== */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        /* ---------- Reveal on scroll ---------- */
        var revealEls = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window && revealEls.length) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

            revealEls.forEach(function (el) { io.observe(el); });
        } else {
            revealEls.forEach(function (el) { el.classList.add('is-visible'); });
        }

        /* ---------- Navbar shadow ---------- */
        var nav = document.querySelector('.site-nav');
        var progress = document.querySelector('.scroll-progress');
        var toTop = document.querySelector('.to-top');

        function onScroll() {
            var y = window.scrollY || document.documentElement.scrollTop;

            if (nav) { nav.classList.toggle('scrolled', y > 20); }

            if (progress) {
                var h = document.documentElement.scrollHeight - window.innerHeight;
                progress.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
            }

            if (toTop) { toTop.classList.toggle('show', y > 500); }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        if (toTop) {
            toTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    });
})();
