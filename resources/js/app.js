import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

import Alpine from 'alpinejs';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Chart from 'chart.js/auto';
import QRCode from 'qrcode';
import { Html5Qrcode } from 'html5-qrcode';

window.Alpine = Alpine;
window.gsap = gsap;
window.Chart = Chart;
window.QRCode = QRCode;
window.Html5Qrcode = Html5Qrcode;

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

document.querySelectorAll('[data-attendance-qr]').forEach((canvas) => {
    QRCode.toCanvas(canvas, canvas.dataset.attendanceQr, {
        width: 280,
        margin: 1,
        errorCorrectionLevel: 'M',
    });
});

document.querySelectorAll('[data-qr-scanner]').forEach((container) => {
    const button = container.querySelector('[data-start-scanner]');
    const region = container.querySelector('[data-scanner-region]');
    const status = container.querySelector('[data-scan-status]');
    let scanner;
    let processing = false;

    button.addEventListener('click', async () => {
        if (scanner) {
            await scanner.stop();
            scanner.clear();
            scanner = null;
            region.classList.add('hidden');
            button.innerHTML = '<i class="fa-solid fa-camera"></i> Buka Kamera';
            status.textContent = 'Kamera dihentikan.';
            return;
        }

        region.id = region.id || `attendance-scanner-${Date.now()}`;
        scanner = new Html5Qrcode(region.id);
        region.classList.remove('hidden');
        button.innerHTML = '<i class="fa-solid fa-stop"></i> Tutup Kamera';
        status.textContent = 'Arahkan kamera ke QR gym.';

        try {
            await scanner.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 220, height: 220 } },
                async (decodedText) => {
                    if (processing) return;

                    processing = true;
                    status.textContent = 'Memproses kehadiran...';

                    try {
                        const response = await window.axios.post(container.dataset.scanUrl, { token: decodedText }, {
                            headers: { 'X-CSRF-TOKEN': container.dataset.csrfToken },
                        });

                        status.textContent = `${response.data.message} (${response.data.time})`;
                        await scanner.stop();
                        scanner.clear();
                        scanner = null;
                        region.classList.add('hidden');
                        button.innerHTML = '<i class="fa-solid fa-camera"></i> Buka Kamera';
                        window.location.reload();
                    } catch (error) {
                        status.textContent = error.response?.data?.message ?? 'QR tidak valid atau gagal mencatat kehadiran.';
                        processing = false;
                    }
                },
                () => {},
            );
        } catch (error) {
            scanner = null;
            region.classList.add('hidden');
            button.innerHTML = '<i class="fa-solid fa-camera"></i> Buka Kamera';
            status.textContent = 'Kamera tidak dapat dibuka. Izinkan akses kamera dan gunakan HTTPS atau localhost.';
        }
    });
});
