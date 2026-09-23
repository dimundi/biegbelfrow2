(() => {
    const ranking = document.querySelector('.ranking-section');
    if (ranking) {
        const edition = ranking.querySelector('#ranking-edition');
        const activities = ranking.querySelectorAll('.ranking-activities button');
        let activity = '1';
        const more = ranking.querySelector('.ranking-more');
        let expanded = false;
        const renderRanking = () => {
            let hasMore = false;
            ranking.querySelectorAll('.rank-panel').forEach(panel => {
                const active = panel.dataset.edition === edition.value && panel.dataset.activity === activity;
                panel.hidden = !active;
                panel.querySelector('.ranking-podium').hidden = expanded;
                panel.querySelector('.ranking-table-wrap').hidden = !expanded;
                panel.querySelectorAll('tr[data-position]').forEach(row => {
                    const extra = Number(row.dataset.position) > 3;
                    row.hidden = false;
                    if (active && extra) hasMore = true;
                });
            });
            more.hidden = !hasMore;
            more.textContent = expanded ? 'Pokaż podium' : 'Pokaż pierwszą dziesiątkę';
            more.setAttribute('aria-expanded', String(expanded));
            activities.forEach(button => button.setAttribute('aria-pressed', String(button.dataset.activity === activity)));
        };
        ranking.querySelector('.ranking-controls').hidden = false;
        edition.addEventListener('change', () => { expanded = false; renderRanking(); });
        activities.forEach(button => button.addEventListener('click', () => {
            activity = button.dataset.activity;
            expanded = false;
            renderRanking();
        }));
        more.addEventListener('click', () => { expanded = !expanded; renderRanking(); });
        renderRanking();
    }
    const gallery = document.querySelector('.participants-gallery');
    const galleryControls = document.querySelector('.participants-controls');
    if (gallery && galleryControls) {
        galleryControls.hidden = false;
        const galleryButtons = galleryControls.querySelectorAll('button');
        const updateGalleryControls = () => {
            galleryButtons[0].disabled = gallery.scrollLeft <= 2;
            galleryButtons[1].disabled = gallery.scrollLeft + gallery.clientWidth >= gallery.scrollWidth - 2;
        };
        galleryControls.addEventListener('click', (event) => {
            const control = event.target.closest('button[data-direction]');
            if (!control) return;
            const photo = gallery.querySelector('.participant-photo');
            gallery.scrollBy({
                left: Number(control.dataset.direction) * (photo.getBoundingClientRect().width + parseFloat(getComputedStyle(gallery).columnGap)),
                behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'
            });
        });
        gallery.addEventListener('scroll', updateGalleryControls, { passive: true });
        window.addEventListener('resize', updateGalleryControls);
        updateGalleryControls();
    }
    const carousel = document.querySelector('.hero-visual[data-slides]');
    const heroImage = document.querySelector('#hero-activity-image');
    if (carousel && heroImage) {
        const slides = JSON.parse(carousel.dataset.slides);
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const images = slides.map(slide => {
            const image = new Image();
            image.src = slide.src;
            return image;
        });
        let index = 0;
        let hovered = false;
        window.setInterval(() => {
            if (document.hidden || reducedMotion.matches || hovered || carousel.contains(document.activeElement)) return;
            const next = (index + 1) % slides.length;
            if (!images[next].complete || !images[next].naturalWidth) return;
            index = next;
            heroImage.src = slides[index].src;
            heroImage.alt = slides[index].alt;
            heroImage.animate([{ opacity: 0.35 }, { opacity: 1 }], { duration: 450 });
        }, 4500);
        carousel.addEventListener('mouseenter', () => { hovered = true; });
        carousel.addEventListener('mouseleave', () => { hovered = false; });
    }
    const button = document.querySelector('.menu-toggle');
    const nav = document.querySelector('#site-nav');
    if (!button || !nav) return;
    button.hidden = false;
    const close = () => { button.setAttribute('aria-expanded', 'false'); nav.classList.remove('is-open'); };
    button.addEventListener('click', () => {
        const open = button.getAttribute('aria-expanded') !== 'true';
        button.setAttribute('aria-expanded', String(open));
        nav.classList.toggle('is-open', open);
    });
    nav.addEventListener('click', (event) => { if (event.target.closest('a')) close(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') { close(); button.focus(); } });
    document.documentElement.classList.add('js');
    const account = document.querySelector('.account-menu');
    if (account) {
        document.addEventListener('click', (event) => { if (!account.contains(event.target)) account.open = false; });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && account.open) {
                account.open = false;
                account.querySelector('summary').focus();
            }
        });
    }
})();
