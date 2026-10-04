const header = document.querySelector('.header');
const nav = document.querySelector('.nav');
const navToggle = document.querySelector('#nav-toggle');
const navBackdrop = document.querySelector('#nav-backdrop');
const navLinks = document.querySelectorAll('.nav a[href^="#"]');
const sections = [...document.querySelectorAll('section[id]')];

const setNavOpen = (open) => {
    nav.classList.toggle('open', open);
    navBackdrop.classList.toggle('open', open);
    document.body.classList.toggle('nav-open', open);
    navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
};

const onScroll = () => {
    header.classList.toggle('scrolled', window.scrollY > 20);
};

const scrollSpy = () => {
    const pos = window.scrollY + 140;
    let current = sections[0]?.id;

    for (const section of sections) {
        if (section.offsetTop <= pos) {
            current = section.id;
        }
    }

    navLinks.forEach((link) => {
        link.classList.toggle('active', link.getAttribute('href') === `#${current}`);
    });
};

window.addEventListener('scroll', onScroll, { passive: true });
window.addEventListener('scroll', scrollSpy, { passive: true });
onScroll();
scrollSpy();

navToggle.addEventListener('click', () => {
    setNavOpen(!nav.classList.contains('open'));
});

navLinks.forEach((link) => {
    link.addEventListener('click', () => setNavOpen(false));
});

navBackdrop.addEventListener('click', () => setNavOpen(false));

document.addEventListener('click', (e) => {
    if (nav.classList.contains('open') && !nav.contains(e.target) && !navToggle.contains(e.target)) {
        setNavOpen(false);
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('open')) {
        setNavOpen(false);
        navToggle.focus();
    }
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

requestAnimationFrame(() => {
    document.querySelector('.hero')?.classList.add('in');
});
