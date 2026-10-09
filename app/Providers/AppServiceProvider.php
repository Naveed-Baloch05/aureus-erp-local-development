<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Livewire\Livewire;
use Webkul\Security\Models\User;

use function Livewire\on;
use function Livewire\store;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Authenticatable::class, User::class);
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Super-admin gate bypass: case-insensitive role check
        // because the Role model's getNameAttribute() ucfirst's the stored name.
        Gate::before(function ($user, string $ability): ?bool {
            if (
                method_exists($user, 'getRoleNames') &&
                $user->getRoleNames()
                    ->map(fn ($r) => strtolower($r))
                    ->contains('super_admin')
            ) {
                return true;
            }

            return null;
        });

        on('dehydrate', function (Component $component): void {
            if (! Livewire::isLivewireRequest()) {
                return;
            }

            if (! store($component)->has('redirect')) {
                return;
            }

            $notifications = session()->pull('filament.notifications');

            if (empty($notifications)) {
                return;
            }

            session()->put('filament.claimed_notifications', $notifications);
        });
    }
}
