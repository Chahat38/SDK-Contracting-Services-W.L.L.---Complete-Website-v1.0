'use strict';

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

function debounce(fn, ms = 100) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

function throttle(fn) {
    let rafId = null;
    return (...args) => {
        if (rafId) return;
        rafId = requestAnimationFrame(() => { fn(...args); rafId = null; });
    };
}

const lerp = (a, b, t) => a + (b - a) * t;

const ease = {
    outExpo: t => t === 1 ? 1 : 1 - Math.pow(2, -10 * t),
    outQuart: t => 1 - Math.pow(1 - t, 4),
    inOutQuad: t => t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2,
};

(function initMobileNav() {
    const toggle = $('#navToggle');
    const nav = $('#mainNav');
    if (!toggle || !nav) return;

    let isOpen = false;

    function openNav() {
        isOpen = true;
        nav.classList.add('open');
        toggle.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeNav() {
        isOpen = false;
        nav.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-controls', 'mainNav');

    toggle.addEventListener('click', () => isOpen ? closeNav() : openNav());

    $$('a', nav).forEach(a => a.addEventListener('click', closeNav));

    document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) closeNav(); });

    document.addEventListener('pointerdown', e => {
        if (isOpen && !nav.contains(e.target) && !toggle.contains(e.target)) closeNav();
    });
})();

(function initStickyHeader() {
    const header = $('.site-header');
    if (!header) return;

    let lastY = 0;
    let hidden = false;

    const onScroll = throttle(() => {
        const y = window.scrollY;
        header.classList.toggle('scrolled', y > 20);

        const delta = y - lastY;
        if (y > 120) {
            if (delta > 6 && !hidden) {
                header.style.transform = 'translateY(-100%)';
                hidden = true;
            } else if (delta < -4 && hidden) {
                header.style.transform = 'translateY(0)';
                hidden = false;
            }
        } else {
            header.style.transform = 'translateY(0)';
            hidden = false;
        }
        lastY = y;
    });

    header.style.transition = 'transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease';
    window.addEventListener('scroll', onScroll, { passive: true });
})();

(function initCounters() {
    const counters = $$('[data-counter]');
    if (!counters.length) return;

    function animateCounter(el) {
        const target = parseInt(el.dataset.counter, 10);
        const suffix = el.dataset.suffix ?? '+';
        const duration = parseInt(el.dataset.duration, 10) || 1800;
        const start = performance.now();

        function tick(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const value = Math.round(ease.outExpo(progress) * target);
            el.textContent = value.toLocaleString() + (progress < 1 ? '' : suffix);
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            animateCounter(e.target);
            io.unobserve(e.target);
        });
    }, { threshold: 0.4 });

    counters.forEach(el => io.observe(el));
})();

(function initReveal() {
    if (!('IntersectionObserver' in window)) return;

    const SELECTORS = [
        '.project-card', '.service-card', '.stat-card',
        '.stat-box', '.team-card', '.contact-detail',
        '.values-list li', '.feature-item', '.hero-text > *',
    ];

    const els = $$(SELECTORS.join(', '));
    if (!els.length) return;

    const groups = new Map();
    els.forEach(el => {
        const key = el.parentElement;
        if (!groups.has(key)) groups.set(key, []);
        groups.get(key).push(el);
    });

    groups.forEach(children => {
        children.forEach((el, i) => {
            el.dataset.revealIndex = i;
        });
    });

    const style = document.createElement('style');
    style.textContent = `
        [data-reveal-index] {
            opacity: 0;
            transform: translateY(32px) scale(0.98);
            transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }
        [data-reveal-index].revealed {
            opacity: 1;
            transform: translateY(0) scale(1);
            will-change: auto;
        }
    `;
    document.head.appendChild(style);

    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const delay = parseInt(e.target.dataset.revealIndex, 10) * 80;
            setTimeout(() => e.target.classList.add('revealed'), delay);
            io.unobserve(e.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    els.forEach(el => io.observe(el));
})();

(function initSmoothScroll() {
    $$('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id = a.getAttribute('href');
            if (id === '#') return;
            const target = $(id);
            if (!target) return;
            e.preventDefault();

            const header = $('.site-header');
            const offset = header ? header.offsetHeight + 16 : 80;
            const targetTop = target.getBoundingClientRect().top + window.scrollY - offset;

            window.scrollTo({ top: targetTop, behavior: 'smooth' });

            history.pushState(null, '', id);
        });
    });
})();

(function initParallax() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const layers = $$('[data-parallax]');
    if (!layers.length) return;

    const onScroll = throttle(() => {
        const scrollY = window.scrollY;
        layers.forEach(el => {
            const speed = parseFloat(el.dataset.parallax) || 0.2;
            const rect = el.getBoundingClientRect();
            const center = rect.top + rect.height / 2;
            const offset = (center - window.innerHeight / 2) * speed;
            el.style.transform = `translateY(${offset.toFixed(2)}px)`;
        });
    });

    window.addEventListener('scroll', onScroll, { passive: true });
})();

(function initMagneticButtons() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (window.matchMedia('(hover: none)').matches) return;

    $$('[data-magnetic]').forEach(btn => {
        btn.addEventListener('mousemove', e => {
            const rect = btn.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = (e.clientX - cx) * 0.25;
            const dy = (e.clientY - cy) * 0.25;
            btn.style.transform = `translate(${dx.toFixed(1)}px, ${dy.toFixed(1)}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transition = 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
            btn.style.transform = 'translate(0, 0)';
            setTimeout(() => { btn.style.transition = ''; }, 500);
        });
    });
})();

(function initCursorTrailer() {
    if (!document.body.dataset.cursorTrailer) return;
    if (window.matchMedia('(hover: none)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const dot = document.createElement('div');
    dot.id = 'cursor-dot';
    Object.assign(dot.style, {
        position: 'fixed', top: 0, left: 0,
        width: '10px', height: '10px',
        borderRadius: '50%',
        background: 'var(--accent, #6C63FF)',
        pointerEvents: 'none',
        zIndex: '9999',
        transform: 'translate(-50%, -50%)',
        transition: 'opacity 0.3s, width 0.3s, height 0.3s',
        willChange: 'transform',
    });
    document.body.appendChild(dot);

    let mx = 0, my = 0, dx = 0, dy = 0;

    document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; }, { passive: true });

    (function loop() {
        dx = lerp(dx, mx, 0.15);
        dy = lerp(dy, my, 0.15);
        dot.style.transform = `translate(${dx.toFixed(1)}px, ${dy.toFixed(1)}px) translate(-50%, -50%)`;
        requestAnimationFrame(loop);
    })();

    $$('a, button, [role="button"], [data-magnetic]').forEach(el => {
        el.addEventListener('mouseenter', () => {
            dot.style.width = '24px';
            dot.style.height = '24px';
            dot.style.opacity = '0.6';
        });
        el.addEventListener('mouseleave', () => {
            dot.style.width = '10px';
            dot.style.height = '10px';
            dot.style.opacity = '1';
        });
    });
})();

(function initActiveNav() {
    const navLinks = $$('.site-nav a[href^="#"], #mainNav a[href^="#"]');
    if (!navLinks.length) return;

    const sectionIds = navLinks.map(a => a.getAttribute('href')).filter(h => h !== '#');
    const sections = sectionIds.map(id => $(id)).filter(Boolean);
    if (!sections.length) return;

    const header = $('.site-header');

    const onScroll = throttle(() => {
        const offset = (header ? header.offsetHeight : 0) + 32;
        let current = '';

        sections.forEach(sec => {
            if (window.scrollY >= sec.offsetTop - offset) current = '#' + sec.id;
        });

        navLinks.forEach(a => {
            a.classList.toggle('active', a.getAttribute('href') === current);
        });
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();

(function initFlashMessages() {
    $$('.flash-auto-hide').forEach((el, i) => {
        const delay = 3500 + i * 400;
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 500);
        }, delay);
    });
})();

$$('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});

(function initContactForm() {
    const form = $('#contactForm');
    if (!form) return;

    function showError(input, msg) {
        input.classList.add('is-error');
        input.setAttribute('aria-invalid', 'true');
        let err = input.parentElement.querySelector('.field-error');
        if (!err) {
            err = document.createElement('span');
            err.className = 'field-error';
            err.setAttribute('role', 'alert');
            err.setAttribute('aria-live', 'polite');
            input.parentElement.appendChild(err);
        }
        err.textContent = '⚠ ' + msg;
        err.style.display = 'block';
    }

    function clearError(input) {
        input.classList.remove('is-error');
        input.removeAttribute('aria-invalid');
        const err = input.parentElement.querySelector('.field-error');
        if (err) err.style.display = 'none';
    }

    const phoneInput = $('#phone', form);
    if (phoneInput) {
        phoneInput.addEventListener('input', () => {
            const pos = phoneInput.selectionStart;
            const cleaned = phoneInput.value.replace(/[^0-9\s+\-()]/g, '');
            if (cleaned !== phoneInput.value) {
                phoneInput.value = cleaned;
                phoneInput.setSelectionRange(pos - 1, pos - 1);
            }
        });
        phoneInput.addEventListener('blur', () => validatePhone(phoneInput));
    }

    const nameInput = $('#name', form);
    if (nameInput) {
        nameInput.addEventListener('blur', () => {
            const v = nameInput.value.trim();
            if (v && v.length < 2) showError(nameInput, 'Name must be at least 2 characters.');
            else clearError(nameInput);
        });
    }

    const emailInput = $('#email', form);
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailInput) {
        emailInput.addEventListener('blur', () => {
            const v = emailInput.value.trim();
            if (v && !emailRe.test(v)) showError(emailInput, 'Please enter a valid email address.');
            else clearError(emailInput);
        });
    }

    const msgInput = $('#message', form);
    const msgHint = form.querySelector('.char-count-hint');
    if (msgInput && msgHint) {
        msgInput.addEventListener('input', () => {
            const len = msgInput.value.length;
            msgHint.textContent = len < 10
                ? `${10 - len} more character${10 - len === 1 ? '' : 's'} needed`
                : `${len} characters ✓`;
            msgHint.style.color = len >= 10 ? 'var(--success)' : 'var(--muted)';
        });
    }

    form.addEventListener('submit', e => {
        let valid = true;
        const errors = [];

        if (nameInput) {
            const v = nameInput.value.trim();
            if (v.length < 2) {
                showError(nameInput, 'Please enter your full name.');
                errors.push(nameInput);
                valid = false;
            } else clearError(nameInput);
        }

        if (emailInput) {
            if (!emailRe.test(emailInput.value.trim())) {
                showError(emailInput, 'Please enter a valid email address.');
                errors.push(emailInput);
                valid = false;
            } else clearError(emailInput);
        }

        if (phoneInput && phoneInput.value.trim()) {
            if (!validatePhone(phoneInput)) { errors.push(phoneInput); valid = false; }
        }

        if (msgInput && msgInput.value.trim().length < 10) {
            showError(msgInput, 'Please write at least 10 characters.');
            errors.push(msgInput);
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            const first = errors[0];
            const header = $('.site-header');
            const offset = (header ? header.offsetHeight : 0) + 24;
            const top = first.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
            setTimeout(() => first.focus(), 400);
        }
    });

    function validatePhone(input) {
        const v = input.value.trim();
        if (!v) { clearError(input); return true; }
        if (!/^\+?[\d\s\-()]{7,20}$/.test(v)) {
            showError(input, 'Enter a valid phone number (7–20 digits, spaces, + or - allowed).');
            return false;
        }
        clearError(input);
        return true;
    }
})();

(function initLazyImages() {
    $$('img[loading="lazy"]').forEach(img => {
        img.style.transition = 'opacity 0.4s ease';
        if (!img.complete) {
            img.style.opacity = '0';
            img.addEventListener('load', () => { img.style.opacity = '1'; }, { once: true });
        }
    });
})();

(function initSplitText() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    $$('[data-split-animate]').forEach(el => {
        const words = el.textContent.trim().split(/\s+/);
        el.textContent = '';
        el.style.overflow = 'hidden';

        words.forEach((word, i) => {
            const wrapper = document.createElement('span');
            wrapper.style.cssText = 'display:inline-block; overflow:hidden; vertical-align:bottom;';

            const inner = document.createElement('span');
            inner.textContent = word + '\u00A0';
            inner.style.cssText = `
                display: inline-block;
                transform: translateY(110%);
                transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1) ${i * 80}ms;
            `;

            wrapper.appendChild(inner);
            el.appendChild(wrapper);

            requestAnimationFrame(() => {
                requestAnimationFrame(() => { inner.style.transform = 'translateY(0)'; });
            });
        });
    });
})();

(function respectReducedMotion() {
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    $$('[data-reveal-index]').forEach(el => {
        el.style.opacity = '1';
        el.style.transform = 'none';
        el.style.transition = 'none';
    });
})();