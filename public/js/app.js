(function () {
    // ===== Reveal on scroll =====
    const els = Array.from(document.querySelectorAll("[data-reveal]"));
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    const delay = parseInt(
                        e.target.getAttribute("data-reveal") || "0",
                        10,
                    );
                    setTimeout(
                        () => e.target.classList.add("reveal-visible"),
                        delay,
                    );
                    io.unobserve(e.target);
                }
            });
        },
        { threshold: 0.12 },
    );

    els.forEach((el) => io.observe(el));

    // ===== Countdown =====
    const timer = document.getElementById("countdown-timer");
    if (timer) {
        const daysEl = timer.querySelector("[data-days]");
        const hoursEl = timer.querySelector("[data-hours]");
        const minutesEl = timer.querySelector("[data-minutes]");
        const secondsEl = timer.querySelector("[data-seconds]");

        // D'après ta page: "28-29 Janvier 2025 - Djibouti"
        // (Si tu veux une autre date cible, remplace ici)
        const target = new Date("2025-01-28T00:00:00+03:00");

        function pad(n) {
            return String(n).padStart(2, "0");
        }

        function tick() {
            const now = new Date();
            let diff = target.getTime() - now.getTime();

            if (diff <= 0) {
                if (daysEl) daysEl.textContent = "0";
                if (hoursEl) hoursEl.textContent = "0";
                if (minutesEl) minutesEl.textContent = "0";
                if (secondsEl) secondsEl.textContent = "0";
                return;
            }

            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / (3600 * 24));
            const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (daysEl) daysEl.textContent = String(days);
            if (hoursEl) hoursEl.textContent = pad(hours);
            if (minutesEl) minutesEl.textContent = pad(minutes);
            if (secondsEl) secondsEl.textContent = pad(seconds);
        }

        tick();
        setInterval(tick, 1000);
    }
})();

document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.getElementById("siteNavbar");
    if (!navbar) return;

    const navOnHero = document.body.dataset.navOnHero === "1";
    const threshold = 30;

    const applyState = () => {
        const scrolled = window.scrollY > threshold;

        // Si page "hero" (home), on alterne selon scroll
        if (navOnHero) {
            navbar.classList.toggle("is-scrolled", scrolled);
            navbar.classList.toggle("is-top", !scrolled);
            return;
        }

        // Sinon (autres pages), on garde le style scrolled en permanence
        navbar.classList.add("is-scrolled");
        navbar.classList.remove("is-top");
    };

    applyState();
    window.addEventListener("scroll", applyState, { passive: true });
});

// ===== Programme tabs (Jour 1 / Jour 2) =====
document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector("[data-program-tabs]");
    if (!root) return;

    const buttons = Array.from(root.querySelectorAll("[data-tab]"));
    const panels = Array.from(root.querySelectorAll("[data-tab-content]"));

    const setActive = (tab) => {
        buttons.forEach((b) =>
            b.classList.toggle("is-active", b.dataset.tab === tab),
        );
        panels.forEach((p) => {
            const show = p.dataset.tabContent === tab;
            p.classList.toggle("d-none", !show);
        });
    };

    buttons.forEach((btn) =>
        btn.addEventListener("click", () => setActive(btn.dataset.tab)),
    );

    // état initial
    if (buttons.length) setActive(buttons[0].dataset.tab);
});

// ===== Inscription =====
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("[data-insc-form]");
    if (!form) return;

    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;

    form.addEventListener("submit", () => {
        btn.disabled = true;
        btn.dataset.originalText = btn.textContent;
        btn.textContent = "Envoi en cours...";
    });
});

// ===== Admin Sidebar (mobile drawer) =====
document.addEventListener("DOMContentLoaded", () => {
    const shell = document.getElementById("adminShell");
    if (!shell) return;

    const overlay = document.getElementById("adminOverlay");
    const openBtn = document.getElementById("adminOpenBtn");
    const closeBtn = document.getElementById("adminCloseBtn");

    const open = () => {
        shell.classList.add("is-open");
        document.documentElement.classList.add("no-scroll");
        document.body.style.overflow = "hidden";
    };

    const close = () => {
        shell.classList.remove("is-open");
        document.documentElement.classList.remove("no-scroll");
        document.body.style.overflow = "";
    };

    openBtn && openBtn.addEventListener("click", open);
    closeBtn && closeBtn.addEventListener("click", close);
    overlay && overlay.addEventListener("click", close);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") close();
    });
});

// ===== Admin dates =====
document.addEventListener("change", (e) => {
    const el = e.target;
    if (el && el.matches("[data-admin-autosubmit]")) {
        const form = el.closest("form");
        if (form) form.submit();
    }
});

// ===== Admin Agenda =====
document.addEventListener("DOMContentLoaded", () => {
    const filter = document.querySelector("[data-agenda-filter]");
    if (!filter || !filter.form) return;

    filter.addEventListener("change", () => {
        filter.form.submit();
    });
});

// --- Programme tabs (public) ---
(() => {
    const root = document.querySelector("[data-program-tabs]");
    if (!root) return;

    const buttons = root.querySelectorAll("[data-tab]");
    const panels = root.querySelectorAll("[data-tab-content]");

    const openTab = (key) => {
        buttons.forEach((btn) =>
            btn.classList.toggle(
                "is-active",
                btn.getAttribute("data-tab") === key,
            ),
        );
        panels.forEach((p) =>
            p.classList.toggle(
                "d-none",
                p.getAttribute("data-tab-content") !== key,
            ),
        );
    };

    // init = bouton déjà actif sinon premier
    const activeBtn =
        root.querySelector(".programme-tab.is-active") || buttons[0];
    if (activeBtn) openTab(activeBtn.getAttribute("data-tab"));

    buttons.forEach((btn) => {
        btn.addEventListener("click", () =>
            openTab(btn.getAttribute("data-tab")),
        );
    });
})();

// ===============================
// Countdown Home (EventDate + Agenda bounds)
// ===============================
(function () {
    function pad2(n) {
        return String(n).padStart(2, "0");
    }

    function setNumbers(root, d, h, m, s) {
        const elD = root.querySelector("[data-days]");
        const elH = root.querySelector("[data-hours]");
        const elM = root.querySelector("[data-minutes]");
        const elS = root.querySelector("[data-seconds]");
        if (elD) elD.textContent = String(d);
        if (elH) elH.textContent = pad2(h);
        if (elM) elM.textContent = pad2(m);
        if (elS) elS.textContent = pad2(s);
    }

    function setLabel(text) {
        const label = document.querySelector("[data-countdown-label]");
        if (label) label.textContent = text;
    }

    function startCountdown() {
        const root = document.getElementById("countdown-timer");
        if (!root) return;

        const startIso = root.getAttribute("data-countdown-start");
        const endIso = root.getAttribute("data-countdown-end");

        // si pas de data => rien à faire
        if (!startIso || !endIso) {
            setNumbers(root, 0, 0, 0, 0);
            return;
        }

        const start = new Date(startIso);
        const end = new Date(endIso);

        if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
            console.warn("[Countdown] Invalid dates:", { startIso, endIso });
            setNumbers(root, 0, 0, 0, 0);
            return;
        }

        let timerId = null;

        function render(ms) {
            const totalSeconds = Math.max(0, Math.floor(ms / 1000));
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            setNumbers(root, days, hours, minutes, seconds);
        }

        function tick() {
            const now = new Date();

            if (now < start) {
                setLabel("Lancement officiel de Mobile ID — Début dans :");
                render(start.getTime() - now.getTime());
                return;
            }

            if (now >= start && now <= end) {
                setLabel("Lancement officiel de Mobile ID, Se termine dans :");
                render(end.getTime() - now.getTime());
                return;
            }

            setLabel("Lancement officiel de Mobile ID — Événement terminé");
            setNumbers(root, 0, 0, 0, 0);
            if (timerId) clearInterval(timerId);
        }

        tick();
        timerId = setInterval(tick, 1000);
    }

    // Important: si DOMContentLoaded est déjà passé
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", startCountdown);
    } else {
        startCountdown();
    }
})();
