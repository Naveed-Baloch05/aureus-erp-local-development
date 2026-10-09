@props([
    'after' => null,
    'heading' => null,
    'subheading' => null,
])
@php
    use Filament\Livewire\SimpleUserMenu;
    use Filament\Support\Enums\Width;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;

    $livewire ??= null;

    $renderHookScopes = $livewire?->getRenderHookScopes();
    $maxContentWidth ??= (filament()->getSimplePageMaxContentWidth() ?? Width::Large);

    if (is_string($maxContentWidth)) {
        $maxContentWidth = Width::tryFrom($maxContentWidth) ?? $maxContentWidth;
    }
@endphp

@push('styles')
    {{-- Bootstrap 5 (layout utilities only – no JS needed for auth) --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    />
    {{-- Auth custom styles --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="fi-simple-layout auth-page">
        @if (($hasTopbar ?? true) && filament()->auth()->check())
            <a href="#fi-main-content" class="fi-skip-link fi-sr-only">
                {{ __('filament-panels::layout.skip_to_content.label') }}
            </a>
        @endif

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        @if (($hasTopbar ?? true) && filament()->auth()->check())
            <div class="fi-simple-layout-header">
                @if (filament()->hasDatabaseNotifications())
                    @livewire(filament()->getDatabaseNotificationsLivewireComponent(), [
                        'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                        'position' => \Filament\Enums\DatabaseNotificationsPosition::Topbar,
                    ])
                @endif
                @if (filament()->hasUserMenu())
                    @livewire(SimpleUserMenu::class)
                @endif
            </div>
        @endif

        <div class="fi-simple-main-ctn auth-card">
            <main
                id="fi-main-content"
                tabindex="-1"
                @class([
                    'fi-simple-main',
                    ($maxContentWidth instanceof Width) ? "fi-width-{$maxContentWidth->value}" : $maxContentWidth,
                ])
            >
                {{-- ── Header ── --}}
                <div class="auth-header">
                    <div class="auth-logo">
                        @if (filament()->getBrandLogo())
                            <img src="{{ filament()->getBrandLogo() }}" alt="{{ filament()->getBrandName() }}">
                        @else
                            <span class="auth-title">{{ filament()->getBrandName() }}</span>
                        @endif
                    </div>

                    @if ($heading)
                        <h1 class="auth-title">{{ $heading }}</h1>
                    @endif

                    @if ($subheading)
                        <p class="auth-subtitle">{{ $subheading }}</p>
                    @endif
                </div>

                {{-- ── Form slot ── --}}
                {{ $slot }}

                {{-- ── Footer ── --}}
                <div class="auth-footer">
                    <p>&copy; {{ date('Y') }} {{ filament()->getBrandName() }}. All rights reserved.</p>
                </div>
            </main>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::FOOTER, scopes: $renderHookScopes) }}
        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>
</x-filament-panels::layout.base>
