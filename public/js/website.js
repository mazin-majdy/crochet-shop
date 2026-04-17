/* ═══════════════════════════════════════════════════════════════════
   لمسة خيط — Enhanced Website JS
═══════════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

    /* ── 1. Custom Cursor ───────────────────────────────────────────── */
    const dot = document.getElementById('cursor-dot');
    const ring = document.getElementById('cursor-ring');

    if (dot && ring && window.matchMedia('(hover: hover)').matches) {
        let mx = 0, my = 0, rx = 0, ry = 0;
        let rafId;

        document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });

        const animCursor = () => {
            dot.style.left = mx + 'px';
            dot.style.top = my + 'px';
            rx += (mx - rx) * 0.14;
            ry += (my - ry) * 0.14;
            ring.style.left = rx + 'px';
            ring.style.top = ry + 'px';
            rafId = requestAnimationFrame(animCursor);
        };
        animCursor();

        document.querySelectorAll('a, button, .product-card, .cat-card, .filter-tab').forEach(el => {
            el.addEventListener('mouseenter', () => document.body.classList.add('cursor-link'));
            el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-link'));
        });

        document.addEventListener('mouseleave', () => {
            dot.style.opacity = '0';
            ring.style.opacity = '0';
        });
        document.addEventListener('mouseenter', () => {
            dot.style.opacity = '1';
            ring.style.opacity = '0.5';
        });
    }

    /* ── 2. Scroll Progress Bar ─────────────────────────────────────── */
    const progressBar = document.getElementById('scroll-progress');
    const updateProgress = () => {
        if (!progressBar) return;
        const scrollTop = window.scrollY;
        const docH = document.documentElement.scrollHeight - window.innerHeight;
        progressBar.style.transform = `scaleX(${scrollTop / docH})`;
    };
    window.addEventListener('scroll', updateProgress, { passive: true });

    /* ── 3. Navbar scroll effect ────────────────────────────────────── */
    const navbar = document.querySelector('.site-navbar');
    const handleNavScroll = () => {
        if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 30);
    };
    window.addEventListener('scroll', handleNavScroll, { passive: true });

    /* ── 4. Back to Top ─────────────────────────────────────────────── */
    const backTop = document.getElementById('back-top');
    if (backTop) {
        window.addEventListener('scroll', () => {
            backTop.classList.toggle('show', window.scrollY > 400);
        }, { passive: true });
        backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    /* ── 5. Mobile Menu ─────────────────────────────────────────────── */
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('open');
            hamburger.classList.toggle('open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        });
        mobileMenu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                hamburger.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    }

    /* ── 6. Intersection Observer — Scroll Reveal ───────────────────── */
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('revealed');
                revealObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

    /* ── 7. Animated Counter ────────────────────────────────────────── */
    const counterObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const end = parseInt(el.dataset.count, 10);
            const dur = 1800;
            const step = 16;
            const inc = end / (dur / step);
            let cur = 0;
            const timer = setInterval(() => {
                cur = Math.min(cur + inc, end);
                el.textContent = Math.floor(cur) + (el.dataset.suffix || '');
                if (cur >= end) clearInterval(timer);
            }, step);
            counterObs.unobserve(el);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-count]').forEach(el => counterObs.observe(el));

    /* ── 8. Quick View Modal ────────────────────────────────────────── */
    const qvOverlay = document.getElementById('qv-overlay');
    const qvImg = document.getElementById('qv-img');
    const qvCat = document.getElementById('qv-cat');
    const qvName = document.getElementById('qv-name');
    const qvPrice = document.getElementById('qv-price');
    const qvDesc = document.getElementById('qv-desc');
    const qvWa = document.getElementById('qv-wa');
    const qvDetail = document.getElementById('qv-detail');

    window.openQuickView = (data) => {
        if (!qvOverlay) return;
        qvImg.innerHTML = data.image
            ? `<img src="${data.image}" alt="${data.name}">`
            : `<span style="font-size:8rem">${data.emoji || '🎁'}</span>`;
        qvCat.textContent = data.cat || '';
        qvName.textContent = data.name;
        qvPrice.textContent = data.price + ' ₪';
        qvDesc.textContent = data.desc || '';
        const msg = encodeURIComponent(`السلام عليكم، أريد طلب: ${data.name} — السعر: ${data.price} ₪`);
        if (qvWa) qvWa.href = `https://wa.me/${data.wa || '970591234567'}?text=${msg}`;
        if (qvDetail) qvDetail.href = data.url || '#';
        qvOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    window.closeQuickView = () => {
        if (qvOverlay) qvOverlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    if (qvOverlay) {
        qvOverlay.addEventListener('click', e => {
            if (e.target === qvOverlay) closeQuickView();
        });
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeQuickView();
    });

    /* ── 9. Magnetic Buttons ────────────────────────────────────────── */
    document.querySelectorAll('.magnetic').forEach(btn => {
        btn.addEventListener('mousemove', e => {
            const r = btn.getBoundingClientRect();
            const dx = (e.clientX - r.left - r.width / 2) * 0.25;
            const dy = (e.clientY - r.top - r.height / 2) * 0.25;
            btn.style.transform = `translate(${dx}px, ${dy}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = '';
            btn.style.transition = 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
        });
    });

    /* ── 10. Toast notifications ───────────────────────────────────── */
    window.showToast = (msg, type = 'default', duration = 3000) => {
        const container = document.getElementById('toast-container')
            || (() => {
                const c = document.createElement('div');
                c.id = 'toast-container';
                c.className = 'toast-container';
                document.body.appendChild(c);
                return c;
            })();
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        const icons = { success: '✅', warning: '⚠️', default: '✨' };
        t.innerHTML = `<span>${icons[type] || icons.default}</span>${msg}`;
        container.appendChild(t);
        setTimeout(() => {
            t.classList.add('out');
            setTimeout(() => t.remove(), 350);
        }, duration);
    };

    /* ── 11. Particle thread effect on hero ────────────────────────── */
    const heroCanvas = document.getElementById('hero-canvas');
    if (heroCanvas) {
        const ctx = heroCanvas.getContext('2d');
        let W, H, particles = [];

        const resize = () => {
            W = heroCanvas.width = heroCanvas.offsetWidth;
            H = heroCanvas.height = heroCanvas.offsetHeight;
        };
        resize();
        window.addEventListener('resize', resize);

        const colors = ['#d4af37', '#7b1113', '#f1c40f', '#422018'];
        for (let i = 0; i < 24; i++) {
            particles.push({
                x: Math.random() * 1200,
                y: Math.random() * 700,
                r: Math.random() * 1.8 + 0.4,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4,
                color: colors[Math.floor(Math.random() * colors.length)],
                alpha: Math.random() * 0.4 + 0.1,
            });
        }

        const draw = () => {
            ctx.clearRect(0, 0, W, H);
            particles.forEach(p => {
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0 || p.x > W) p.vx *= -1;
                if (p.y < 0 || p.y > H) p.vy *= -1;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.globalAlpha = p.alpha;
                ctx.fill();
            });
            // draw threads between close particles
            ctx.globalAlpha = 1;
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 180) {
                        ctx.beginPath();
                        ctx.strokeStyle = particles[i].color;
                        ctx.globalAlpha = (1 - dist / 180) * 0.15;
                        ctx.lineWidth = 0.6;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(draw);
        };
        draw();
    }

    /* ── 12. Smooth anchor scroll for in-page links ──────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ── 13. Image lazy load ─────────────────────────────────────────── */
    const imgObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting && e.target.dataset.src) {
                e.target.src = e.target.dataset.src;
                delete e.target.dataset.src;
                imgObs.unobserve(e.target);
            }
        });
    });
    document.querySelectorAll('img[data-src]').forEach(i => imgObs.observe(i));

    console.log('%c🧵 لمسة خيط', 'font-size:18px;color:#7b1113;font-weight:bold;');
});
