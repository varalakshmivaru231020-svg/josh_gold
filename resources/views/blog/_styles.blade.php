{{-- Styles shared by the Updates list (blog/index) and an update (blog/show). Theme tokens: public/css/bullion-theme.css --}}
<style>
    .upd-page { padding: 32px 0 64px; }
    @media (min-width: 768px) { .upd-page { padding: 56px 0 88px; } }

    /* Cover image, with a gold placeholder when a post has no image */
    .upd-cover { position: relative; overflow: hidden; aspect-ratio: 16 / 10; background: var(--jb-panel-3); }
    .upd-cover img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .6s cubic-bezier(.2, .7, .2, 1); }
    .upd-cover--empty {
        background:
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64' fill='none' stroke='%23b8913f' stroke-width='1.6' stroke-linejoin='round'%3E%3Cpath d='M14 12h36l10 13-28 29L4 25z'/%3E%3Cpath d='M4 25h56M24 12l-5 13 13 29 13-29-5-13'/%3E%3C/svg%3E") center 40% / 58px 58px no-repeat,
            radial-gradient(130% 100% at 50% 0%, #fffaf0 0%, #f7ecd2 55%, #efdcae 100%);
        box-shadow: inset 0 0 0 1px rgba(184, 145, 63, .12);
    }
    .upd-cover--empty::after {
        content: "Josha Gold & Diamond"; position: absolute; left: 12px; right: 12px; top: calc(40% + 42px);
        text-align: center; color: var(--jb-gold-strong); font: 700 10.5px/1.2 var(--jb-sans); letter-spacing: .24em; text-transform: uppercase;
    }
    .upd-cover--sm { width: 76px; height: 76px; flex-shrink: 0; aspect-ratio: auto; border-radius: 12px; border: 1px solid var(--jb-line); }
    .upd-cover--sm.upd-cover--empty { background-position: center, center; background-size: 30px 30px, auto; }
    .upd-cover--sm.upd-cover--empty::after { display: none; }

    /* Small date label */
    .upd-date { display: inline-flex; align-items: center; gap: 8px; color: var(--jb-gold); font-size: 11px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
    .upd-date::before { content: ""; width: 18px; height: 1px; background: currentColor; }

    /* Card in a grid */
    .upd-grid { display: grid; gap: 22px; grid-template-columns: 1fr; }
    @media (min-width: 640px) { .upd-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .upd-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 28px; } }

    .upd-card {
        display: flex; flex-direction: column; height: 100%; overflow: hidden; text-decoration: none; color: inherit;
        background: var(--jb-surface); border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); box-shadow: var(--jb-shadow);
        transition: transform .25s, box-shadow .25s, border-color .25s;
    }
    .upd-card:hover { color: inherit; }
    .upd-card__body { display: flex; flex-direction: column; flex: 1; padding: 20px 22px 22px; }
    .upd-card__title {
        margin: 10px 0 8px !important; font-family: var(--jb-serif); font-size: 19px; font-weight: 600; line-height: 1.35; color: var(--jb-ink);
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .upd-card__excerpt {
        margin: 0 0 18px !important; color: var(--jb-muted); font-size: 14px; line-height: 1.65;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .upd-more { margin-top: auto; display: inline-flex; align-items: center; gap: 8px; color: var(--jb-gold-strong); font-size: 13px; font-weight: 700; letter-spacing: .02em; }
    .upd-more i { font-size: 11px; transition: transform .2s; }
    @media (hover: hover) {
        .upd-card:hover { transform: translateY(-4px); border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); }
        .upd-card:hover .upd-cover img { transform: scale(1.05); }
        .upd-card:hover .upd-more i { transform: translateX(4px); }
    }
    .upd-card:focus-visible { outline: 2px solid var(--jb-gold); outline-offset: 3px; }

    /* Section heading inside the page */
    .upd-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin: 0 0 22px; }
    .upd-head h2 { margin: 6px 0 0 !important; font-size: clamp(22px, 2.4vw, 28px); font-weight: 600; color: var(--jb-ink); }
    .upd-head__count { color: var(--jb-muted); font-size: 13px; white-space: nowrap; }

    @media (prefers-reduced-motion: reduce) {
        .upd-card, .upd-cover img, .upd-more i { transition: none; }
    }
</style>
