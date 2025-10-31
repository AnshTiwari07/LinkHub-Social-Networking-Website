// Lightweight tilt and scroll-reveal utilities (no dependencies)
(function() {
    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    // Tilt effect on hover for elements with class 'tilt-hover'
    function initTilt() {
        var tiltTargets = document.querySelectorAll('.tilt-hover');
        tiltTargets.forEach(function(card) {
            var rect;
            var resetTimeout;
            card.addEventListener('mousemove', function(e) {
                rect = rect || card.getBoundingClientRect();
                var cx = rect.left + rect.width / 2;
                var cy = rect.top + rect.height / 2;
                var dx = e.clientX - cx;
                var dy = e.clientY - cy;
                var percentX = clamp(dx / (rect.width / 2), -1, 1);
                var percentY = clamp(dy / (rect.height / 2), -1, 1);
                var rotateY = percentX * 8; // degrees
                var rotateX = -percentY * 8; // degrees
                card.style.transform = 'perspective(900px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateZ(0)';
            });
            card.addEventListener('mouseleave', function() {
                clearTimeout(resetTimeout);
                resetTimeout = setTimeout(function() {
                    card.style.transform = '';
                }, 60);
            });
        });
    }

    // Scroll reveal for elements with class 'reveal'
    function initReveal() {
        var revealTargets = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            // Fallback: show immediately
            revealTargets.forEach(function(el) { el.classList.add('is-visible'); });
            return;
        }
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealTargets.forEach(function(el) { observer.observe(el); });
    }

    // Parallax background subtle move using mouse
    function initParallax() {
        var hero = document.querySelector('.parallax-hero');
        if (!hero) return;
        hero.addEventListener('mousemove', function(e) {
            var rect = hero.getBoundingClientRect();
            var rx = (e.clientX - rect.left) / rect.width - 0.5;
            var ry = (e.clientY - rect.top) / rect.height - 0.5;
            var bg = hero;
            bg.style.setProperty('--parallax-x', (rx * 8) + 'px');
            bg.style.setProperty('--parallax-y', (ry * 8) + 'px');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initTilt();
        initReveal();
        initParallax();
    });
})();






