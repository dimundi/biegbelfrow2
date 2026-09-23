(() => {
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
