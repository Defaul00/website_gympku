import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

import Alpine from 'alpinejs';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.gsap = gsap;
window.Chart = Chart;

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: localStorage.getItem('theme') === 'dark'
            || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

        init() {
            const forceDark = document.body.hasAttribute('data-force-dark');

            if (forceDark) {
                this.dark = true;
            }

            this.apply();

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('theme') && !forceDark) {
                    this.dark = e.matches;
                    this.apply();
                }
            });
        },

        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            this.apply();
        },

        apply() {
            document.documentElement.classList.toggle('dark', this.dark);
        },
    });

    Alpine.store('toast', {
        items: [],

        push(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type });

            setTimeout(() => {
                this.items = this.items.filter((item) => item.id !== id);
            }, 4200);
        },
    });

    Alpine.data('confirmationModal', () => ({
        open: false,
        title: '',
        message: '',
        confirmText: 'Hapus',
        cancelText: 'Batal',
        loading: false,
        action: null,

        confirm(options) {
            this.title = options.title;
            this.message = options.message;
            this.confirmText = options.confirmText ?? 'Hapus';
            this.cancelText = options.cancelText ?? 'Batal';
            this.action = options.action;
            this.open = true;
        },

        async run() {
            this.loading = true;
            try {
                if (typeof this.action === 'function') {
                    await this.action();
                }
                this.open = false;
            } finally {
                this.loading = false;
            }
        },
    }));
});

document.addEventListener('alpine:initialized', () => {
    if (typeof gsap !== 'undefined') {
        gsap.fromTo('[data-animate]', { opacity: 0, y: 18 }, {
            opacity: 1, y: 0, duration: 0.55, stagger: 0.06,
            ease: 'power3.out',
        });

        document.querySelectorAll('[data-animate-on-view]').forEach((el) => {
            gsap.fromTo(el, { opacity: 0, y: 24 }, {
                opacity: 1, y: 0, duration: 0.6, ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%',
                },
            });
        });
    }
});

Alpine.start();
