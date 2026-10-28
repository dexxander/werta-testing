{{--
    Shared component styles for the public (layouts.app) monetisation, author and
    reviewer pages: wallet, checkout, author studio, earnings, author and reviewer
    profiles, demo login. Uses only the :root tokens from layouts/app.blade.php.
    Include once per page: @include('partials.werta-ui')
--}}
@once
<style>
    .wt-page { max-width: 1100px; margin: 0 auto; padding: 44px 1.5rem 80px; font-family: 'Lato', sans-serif; color: var(--dark); }
    .wt-page--narrow { max-width: 880px; }
    .wt-back { display: inline-flex; align-items: center; gap: 6px; color: var(--muted); text-decoration: none; font-weight: 700; font-size: var(--text-base); margin-bottom: 1.5rem; transition: color .2s; }
    .wt-back:hover { color: var(--primary); }

    .wt-header { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
    .wt-eyebrow { font-size: var(--text-sm); font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: var(--primary); margin-bottom: .5rem; }
    .wt-title { font-family: 'IM Fell English', serif; font-size: var(--text-4xl); line-height: 1.1; color: var(--dark); margin: 0 0 .5rem; }
    .wt-subtitle { font-size: var(--text-base-lg); color: var(--muted); margin: 0; max-width: 640px; line-height: 1.6; }
    .wt-section-title { font-family: 'IM Fell English', serif; font-size: var(--text-2xl); color: var(--dark); margin: 0 0 1rem; }
    .wt-section-sub { font-size: var(--text-base); color: var(--muted); margin: -.6rem 0 1.2rem; }
    .wt-demo-tag { display: inline-flex; align-items: center; gap: 6px; font-size: var(--text-xs); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--primary-dark); background: var(--cream-light); border: 1px solid var(--gold); border-radius: var(--radius-pill); padding: .25rem .8rem; white-space: nowrap; }

    .wt-card { background: #fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring), var(--shadow-md); padding: 1.5rem; }
    .wt-card--flat { box-shadow: var(--card-ring); }
    .wt-card--cream { background: var(--cream-light); }
    .wt-card--dark { background: var(--dark); color: var(--cream); box-shadow: var(--shadow-lg); }
    .wt-card--feature { box-shadow: 0 0 0 2px var(--gold), var(--shadow-md); }
    .wt-card-label { font-size: var(--text-xs); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); margin-bottom: .35rem; }
    .wt-card--dark .wt-card-label { color: rgba(245,239,224,.65); }

    .wt-grid { display: grid; gap: 1.25rem; }
    .wt-grid > * { min-width: 0; }
    .wt-grid-2 { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
    .wt-grid-3 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    .wt-grid-4 { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
    .wt-stack > * + * { margin-top: 1.25rem; }
    .wt-mb { margin-bottom: 2.5rem; }

    .wt-stat-value { font-size: 1.75rem; font-weight: 700; color: var(--dark); line-height: 1.15; }
    .wt-stat-sub { font-size: var(--text-sm); color: var(--muted); margin-top: .2rem; }
    .wt-stat-value.is-good { color: #3a7d44; }
    .wt-stat-value.is-bad { color: #a33; }
    .wt-stat-value.is-gold { color: var(--primary); }

    .wt-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: none; border-radius: var(--radius-md); padding: .7rem 1.4rem; font-weight: 700; font-size: var(--text-base); text-decoration: none; cursor: pointer; transition: background .2s, color .2s, box-shadow .2s; line-height: 1.2; white-space: nowrap; }
    .wt-btn--gold { background: var(--gold); color: var(--dark); }
    .wt-btn--gold:hover { background: var(--primary); color: #fff; }
    .wt-btn--dark { background: var(--primary-dark); color: #fff; }
    .wt-btn--dark:hover { background: var(--dark); color: #fff; }
    .wt-btn--outline { background: #fff; color: var(--primary-dark); box-shadow: inset 0 0 0 1.5px var(--primary); }
    .wt-btn--outline:hover { background: var(--primary); color: #fff; }
    .wt-btn--ghost { background: transparent; color: var(--primary-dark); padding-left: .4rem; padding-right: .4rem; }
    .wt-btn--ghost:hover { color: var(--primary); text-decoration: underline; }
    .wt-btn--sm { padding: .45rem .9rem; font-size: var(--text-sm); }
    .wt-btn--block { width: 100%; white-space: normal; text-align: center; }
    .wt-btn[disabled], .wt-btn.is-disabled { opacity: .5; cursor: not-allowed; pointer-events: none; }

    .wt-badge { display: inline-flex; align-items: center; gap: 4px; font-size: var(--text-xs); font-weight: 700; letter-spacing: .6px; text-transform: uppercase; padding: .2rem .6rem; border-radius: var(--radius-pill); white-space: nowrap; line-height: 1.4; }
    .wt-badge--gold { background: rgba(196,168,64,.18); color: var(--primary-dark); }
    .wt-badge--dark { background: var(--dark); color: var(--gold); }
    .wt-badge--green { background: #e8f3ea; color: #2f6b39; }
    .wt-badge--blue { background: #e9eef8; color: #2d4f8a; }
    .wt-badge--yellow { background: #fbf3d9; color: #7a5d0b; }
    .wt-badge--red { background: #f8e6e6; color: #9a2e2e; }
    .wt-badge--grey { background: #efede8; color: var(--muted); }

    .wt-alert { border-radius: var(--radius-md); padding: .8rem 1rem; font-size: var(--text-base); margin-bottom: 1.5rem; display: flex; gap: .6rem; align-items: flex-start; }
    .wt-alert--ok { background: #e8f3ea; color: #2f6b39; border: 1px solid #bcd9c1; }
    .wt-alert--err { background: #f8e6e6; color: #9a2e2e; border: 1px solid #e6bcbc; }
    .wt-alert--info { background: var(--cream-light); color: var(--primary-dark); border: 1px solid rgba(196,168,64,.5); }

    .wt-list { background: #fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring); overflow: hidden; }
    .wt-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .95rem 1.2rem; border-bottom: 1px solid rgba(123,107,53,.12); color: var(--dark); text-decoration: none; }
    .wt-row:last-child { border-bottom: none; }
    a.wt-row:hover { background: rgba(245,239,224,.6); color: var(--dark); }
    .wt-row-title { font-weight: 700; }
    .wt-row-meta { font-size: var(--text-sm); color: var(--muted); }
    .wt-muted { color: var(--muted); }
    .min-w-0 { min-width: 0; }
    .wt-small { font-size: var(--text-sm); }

    .wt-progress { height: 9px; border-radius: var(--radius-pill); background: var(--cream); overflow: hidden; }
    .wt-progress-fill { height: 100%; border-radius: var(--radius-pill); background: var(--gold); }
    .wt-progress-fill.is-done { background: #3a7d44; }

    .wt-label { display: block; font-size: var(--text-xs); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: .4rem; }
    .wt-input, .wt-select, .wt-textarea { width: 100%; padding: .65rem .9rem; border-radius: var(--radius-md); border: 1px solid rgba(123,107,53,.28); background: var(--cream-light); color: var(--dark); font-size: var(--text-base); outline: none; transition: border-color .2s, box-shadow .2s; font-family: inherit; }
    .wt-input:focus, .wt-select:focus, .wt-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(123,107,53,.15); }
    .wt-help { font-size: var(--text-sm); color: var(--muted); margin-top: .35rem; }
    .wt-inline-form { display: flex; gap: .6rem; flex-wrap: wrap; }
    .wt-inline-form .wt-input { flex: 1 1 180px; }

    .wt-tabs { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .wt-tab { padding: .45rem 1rem; border-radius: var(--radius-pill); font-size: var(--text-sm); font-weight: 700; color: var(--muted); text-decoration: none; background: transparent; box-shadow: inset 0 0 0 1px rgba(123,107,53,.25); transition: all .2s; }
    .wt-tab:hover { color: var(--primary); box-shadow: inset 0 0 0 1px var(--primary); }
    .wt-tab.is-active { background: var(--primary-dark); color: #fff; box-shadow: none; }
    .wt-tab .wt-tab-count { opacity: .7; margin-left: 4px; }

    .wt-steps { display: flex; gap: 4px; }
    .wt-step { flex: 1; min-width: 0; }
    .wt-step-bar { height: 5px; border-radius: var(--radius-pill); background: #e6e1d4; }
    .wt-step.is-done .wt-step-bar { background: var(--gold); }
    .wt-step.is-current .wt-step-bar { background: var(--primary-dark); }
    .wt-step.is-stopped .wt-step-bar { background: #c97b7b; }
    .wt-step-label { font-size: .66rem; color: var(--muted); margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .wt-step.is-current .wt-step-label { color: var(--primary-dark); font-weight: 700; }

    .wt-empty { text-align: center; padding: 3rem 1.5rem; color: var(--muted); }
    .wt-empty i { font-size: 2.2rem; color: var(--gold); display: block; margin-bottom: .6rem; }

    .wt-table { width: 100%; border-collapse: collapse; font-size: var(--text-base); }
    .wt-table th { text-align: left; font-size: var(--text-xs); text-transform: uppercase; letter-spacing: 1px; color: var(--primary); background: var(--cream-light); padding: .7rem 1rem; border-bottom: 1px solid rgba(196,168,64,.35); }
    .wt-table td { padding: .75rem 1rem; border-bottom: 1px solid rgba(123,107,53,.1); vertical-align: middle; }
    .wt-table tr:last-child td { border-bottom: none; }
    .wt-num { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .wt-plus { color: #3a7d44; font-weight: 700; }
    .wt-minus { color: #a33; font-weight: 700; }

    .wt-token { display: inline-flex; align-items: center; gap: 4px; font-weight: 700; }
    .wt-token i { color: var(--gold); }
    .wt-avatar { width: 56px; height: 56px; border-radius: var(--radius-full); border: 2px solid var(--cream); object-fit: cover; flex-shrink: 0; }
    .wt-avatar--lg { width: 96px; height: 96px; border-width: 3px; }
    .wt-chart { position: relative; height: 280px; }

    .wt-pack { position: relative; display: flex; flex-direction: column; height: 100%; }
    .wt-pack-tag { position: absolute; top: -10px; right: 14px; }
    .wt-pack-price { font-size: 1.9rem; font-weight: 700; color: var(--primary-dark); line-height: 1.1; }
    .wt-pack-tokens { font-size: var(--text-base); color: var(--dark); margin: .3rem 0 .2rem; }
    .wt-pack-per { font-size: var(--text-sm); color: var(--muted); margin-bottom: 1rem; }
    .wt-pack form { margin-top: auto; }

    .wt-code { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-weight: 700; letter-spacing: 1.5px; background: var(--cream); border: 1px dashed var(--primary); border-radius: var(--radius-sm); padding: .3rem .7rem; color: var(--primary-dark); }

    .manuscript h2 { font-family: 'IM Fell English', serif; font-size: var(--text-xl); color: var(--dark); margin: 1.6rem 0 .6rem; }
    .manuscript > h2:first-child { margin-top: 0; }
    .manuscript p { margin: 0 0 1rem; line-height: 1.75; }
    .manuscript ul { margin: 0 0 1rem; padding-left: 1.3rem; }
    .manuscript li { margin-bottom: .35rem; }

    @media (max-width: 767px) {
        .wt-page { padding-top: 28px; }
        .wt-title { font-size: 2.1rem; }
        .wt-table th, .wt-table td { padding: .6rem .6rem; }
        .wt-hide-sm { display: none; }
    }
</style>
@endonce
