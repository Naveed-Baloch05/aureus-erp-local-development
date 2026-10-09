@props([
    'livewire' => null,
])

@php
    use Filament\Livewire\Notifications;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;

    $renderHookScopes = $livewire?->getRenderHookScopes();
@endphp

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi',
        'dark' => filament()->hasDarkMode() && filament()->hasDarkModeForced(),
    ])
>
    <head>
        {{ FilamentView::renderHook(PanelsRenderHook::HEAD_START, scopes: $renderHookScopes) }}

        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @if ($favicon = filament()->getFavicon())
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        @php
            $title = trim(strip_tags($livewire?->getTitle() ?? ''));
            $brandName = trim(strip_tags(filament()->getBrandName()));
        @endphp

        <title>
            {{ filled($title) ? $title : null }}
            {{ filled($brandName) && filled($title) ? ' - ' : null }}
            {{ filled($brandName) ? $brandName : null }}
        </title>

        {{ FilamentView::renderHook(PanelsRenderHook::STYLES_BEFORE, scopes: $renderHookScopes) }}

        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }

            [x-cloak='inline-flex'] {
                display: inline-flex !important;
            }

            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }

            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }
        </style>

        @filamentStyles

        {{ filament()->getTheme()->getHtml() }}
        {{ filament()->getFontPreloadHtml() }}
        {{ filament()->getMonoFontPreloadHtml() }}
        {{ filament()->getSerifFontPreloadHtml() }}
        {{ filament()->getFontHtml() }}
        {{ filament()->getMonoFontHtml() }}
        {{ filament()->getSerifFontHtml() }}

        <style>
            :root {
                --font-family: '{!! filament()->getFontFamily() !!}';
                --mono-font-family: '{!! filament()->getMonoFontFamily() !!}';
                --serif-font-family: '{!! filament()->getSerifFontFamily() !!}';
                --sidebar-width: {{ filament()->getSidebarWidth() }};
                --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
                --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
            }

            html.fi {
                --livewire-progress-bar-color: var(--primary-500);
            }
        </style>

        @stack('styles')

        {{-- ================================================================
             Auth page styles — inlined so they load regardless of APP_URL
             ================================================================ --}}
        <style id="aureus-auth-styles">
            /* ── Google Font ── */
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

            /* ── Variables ── */
            :root {
                --auth-primary:       #3b82f6;
                --auth-primary-dark:  #2563eb;
                --auth-accent:        #1d4ed8;
                --auth-bg:            #f0f4ff;
                --auth-card-bg:       #ffffff;
                --auth-text:          #1e293b;
                --auth-muted:         #64748b;
                --auth-border:        #e2e8f0;
                --auth-radius:        1rem;
                --auth-shadow:        0 20px 60px rgba(59,130,246,.14), 0 4px 16px rgba(0,0,0,.06);
                --auth-input-focus:   0 0 0 3px rgba(59,130,246,.20);
            }

            /* ── Body ── */
            .fi-body {
                font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            }

            /* ── Full page layout ── */
            .fi-simple-layout.auth-page {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-height: 100vh !important;
                padding: 1.5rem !important;
                background:
                    radial-gradient(ellipse 80% 60% at 20% -10%, rgba(59,130,246,.18) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 50% at 80% 110%, rgba(29,78,216,.14) 0%, transparent 55%),
                    linear-gradient(160deg, #eef2ff 0%, #f0f9ff 50%, #eff6ff 100%) !important;
                position: relative !important;
                overflow: hidden !important;
            }

            /* decorative blobs */
            .fi-simple-layout.auth-page::before,
            .fi-simple-layout.auth-page::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                filter: blur(60px);
                pointer-events: none;
                z-index: 0;
            }
            .fi-simple-layout.auth-page::before {
                width: 420px; height: 420px;
                background: rgba(59,130,246,.12);
                top: -120px; left: -100px;
            }
            .fi-simple-layout.auth-page::after {
                width: 360px; height: 360px;
                background: rgba(99,102,241,.10);
                bottom: -100px; right: -80px;
            }

            /* ── Card wrapper ── */
            .fi-simple-main-ctn.auth-card {
                position: relative !important;
                z-index: 1 !important;
                width: 100% !important;
                display: flex !important;
                justify-content: center !important;
                animation: authFadeUp .45s cubic-bezier(.22,1,.36,1) both !important;
            }

            @keyframes authFadeUp {
                from { opacity: 0; transform: translateY(18px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ── Card ── */
            .fi-simple-main {
                background: #ffffff !important;
                border-radius: var(--auth-radius) !important;
                box-shadow: var(--auth-shadow) !important;
                padding: 2.5rem 2.5rem 2rem !important;
                width: 100% !important;
                max-width: 460px !important;
                border: 1px solid rgba(226,232,240,.8) !important;
            }

            /* override Filament's fi-width-* max-width utilities */
            .fi-simple-main[class*="fi-width-"] { max-width: 460px !important; }

            /* ── Auth header ── */
            .auth-header {
                text-align: center;
                margin-bottom: 1.75rem;
            }
            .auth-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.1rem;
            }
            .auth-logo img {
                height: 48px;
                width: auto;
                object-fit: contain;
            }
            .auth-logo span.auth-title {
                font-size: 1.6rem;
                font-weight: 700;
                background: linear-gradient(135deg, var(--auth-primary), var(--auth-accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .auth-header > h1.auth-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--auth-text);
                margin: 0 0 .35rem;
                letter-spacing: -.02em;
            }
            .auth-subtitle {
                color: var(--auth-muted);
                font-size: .9rem;
                margin: 0;
            }
            .auth-header::after {
                content: '';
                display: block;
                width: 48px; height: 3px;
                background: linear-gradient(90deg, var(--auth-primary), var(--auth-accent));
                border-radius: 99px;
                margin: .85rem auto 0;
            }

            /* ── Labels ── */
            .fi-simple-main label,
            .fi-simple-main .fi-fo-field-wrp-label {
                font-weight: 500 !important;
                font-size: .875rem !important;
                color: var(--auth-text) !important;
            }

            /* ── Inputs ── */
            .fi-simple-main input[type="email"],
            .fi-simple-main input[type="password"],
            .fi-simple-main input[type="text"],
            .fi-simple-main input[type="number"] {
                width: 100% !important;
                padding: .65rem 1rem !important;
                border: 1.5px solid var(--auth-border) !important;
                border-radius: .6rem !important;
                font-size: .9rem !important;
                color: var(--auth-text) !important;
                background: #fafbff !important;
                transition: border-color .2s, box-shadow .2s !important;
                outline: none !important;
                box-shadow: none !important;
            }
            .fi-simple-main input:focus {
                border-color: var(--auth-primary) !important;
                box-shadow: var(--auth-input-focus) !important;
                background: #fff !important;
            }

            /* ── Primary / submit button ── */
            .fi-simple-main .fi-btn-color-primary,
            .fi-simple-main button[type="submit"],
            .fi-simple-main [wire\:loading] {
                width: 100% !important;
                justify-content: center !important;
                padding: .75rem 1.5rem !important;
                background: linear-gradient(135deg, var(--auth-primary) 0%, var(--auth-accent) 100%) !important;
                color: #fff !important;
                font-weight: 600 !important;
                font-size: .95rem !important;
                border: none !important;
                border-radius: .6rem !important;
                cursor: pointer !important;
                transition: transform .15s, box-shadow .15s !important;
                box-shadow: 0 4px 14px rgba(37,99,235,.4) !important;
            }
            .fi-simple-main .fi-btn-color-primary:hover,
            .fi-simple-main button[type="submit"]:hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 6px 20px rgba(37,99,235,.5) !important;
            }

            /* ── Links ── */
            .fi-simple-main a {
                color: var(--auth-primary) !important;
                font-weight: 500;
                text-decoration: none !important;
            }
            .fi-simple-main a:hover {
                color: var(--auth-accent) !important;
                text-decoration: underline !important;
            }

            /* ── Checkbox ── */
            .fi-simple-main input[type="checkbox"] {
                accent-color: var(--auth-primary);
                width: 1rem; height: 1rem;
                cursor: pointer;
            }

            /* ── Footer ── */
            .auth-footer {
                margin-top: 1.75rem;
                padding-top: 1.25rem;
                border-top: 1px solid var(--auth-border);
                text-align: center;
            }
            .auth-footer p {
                font-size: .8rem;
                color: var(--auth-muted);
                margin: 0;
            }

            /* ── Dark mode ── */
            html.dark .fi-simple-layout.auth-page {
                background:
                    radial-gradient(ellipse 80% 60% at 20% -10%, rgba(59,130,246,.10) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 50% at 80% 110%, rgba(29,78,216,.08) 0%, transparent 55%),
                    linear-gradient(160deg, #0f172a 0%, #0d1b2f 50%, #0f172a 100%) !important;
            }
            html.dark .fi-simple-main {
                background: #1e293b !important;
                border-color: rgba(255,255,255,.08) !important;
                box-shadow: 0 20px 60px rgba(0,0,0,.5) !important;
            }
            html.dark .auth-header > h1.auth-title { color: #f1f5f9 !important; }
            html.dark .auth-subtitle              { color: #94a3b8 !important; }
            html.dark .auth-footer                { border-color: rgba(255,255,255,.08) !important; }
            html.dark .auth-footer p              { color: #64748b !important; }
            html.dark .fi-simple-main label       { color: #cbd5e1 !important; }
            html.dark .fi-simple-main input[type="email"],
            html.dark .fi-simple-main input[type="password"],
            html.dark .fi-simple-main input[type="text"] {
                background: #0f172a !important;
                border-color: rgba(255,255,255,.1) !important;
                color: #f1f5f9 !important;
            }

            /* ── Responsive ── */
            @media (max-width: 480px) {
                .fi-simple-main {
                    padding: 2rem 1.5rem 1.75rem !important;
                    border-radius: .75rem !important;
                }
                .fi-simple-layout.auth-page {
                    align-items: flex-start !important;
                    padding-top: 2rem !important;
                }
                .auth-logo img { height: 40px; }
            }
        </style>

        {{ FilamentView::renderHook(PanelsRenderHook::STYLES_AFTER, scopes: $renderHookScopes) }}

        @if (! filament()->hasDarkMode())
            <script>
                localStorage.setItem('theme', 'light')
            </script>
        @elseif (filament()->hasDarkModeForced())
            <script>
                localStorage.setItem('theme', 'dark')
            </script>
        @else
            <script>
                const loadDarkMode = () => {
                    window.theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

                    if (
                        window.theme === 'dark' ||
                        (window.theme === 'system' &&
                            window.matchMedia('(prefers-color-scheme: dark)')
                                .matches)
                    ) {
                        document.documentElement.classList.add('dark')
                    }
                }

                loadDarkMode()

                document.addEventListener('livewire:navigated', loadDarkMode)
            </script>
        @endif

        {{ FilamentView::renderHook(PanelsRenderHook::HEAD_END, scopes: $renderHookScopes) }}
    </head>

    <body
        {{
            $attributes
                ->merge($livewire?->getExtraBodyAttributes() ?? [], escape: false)
                ->class([
                    'fi-body',
                    'fi-panel-' . filament()->getId(),
                ])
        }}
    >
        {{ FilamentView::renderHook(PanelsRenderHook::BODY_START, scopes: $renderHookScopes) }}

        {{ $slot }}

        @livewire(Notifications::class)

        {{ FilamentView::renderHook(PanelsRenderHook::SCRIPTS_BEFORE, scopes: $renderHookScopes) }}

        @filamentScripts(withCore: true)

        @if (filament()->hasBroadcasting() && config('filament.broadcasting.echo'))
            <script data-navigate-once>
                window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                window.dispatchEvent(new CustomEvent('EchoLoaded'))
            </script>
        @endif

        @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
            <script>
                loadDarkMode()
            </script>
        @endif

        @stack('scripts')

        {{ FilamentView::renderHook(PanelsRenderHook::SCRIPTS_AFTER, scopes: $renderHookScopes) }}

        {{ FilamentView::renderHook(PanelsRenderHook::BODY_END, scopes: $renderHookScopes) }}
    </body>
</html>
