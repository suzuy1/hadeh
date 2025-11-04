<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // Add this line
use App\Models\Inventaris;
use App\Models\Room;        // Tambahkan
use App\Models\Unit;        // Tambahkan
use App\Models\User;        // Tambahkan
use App\Models\Transaction; // Tambahkan
use App\Models\Request;     // Tambahkan
use App\Models\Acquisition; // Tambahkan
use App\Observers\InventarisObserver;
use App\Policies\InventarisPolicy; // Tambahkan ini
use App\Policies\RoomPolicy;        // Tambahkan
use App\Policies\UnitPolicy;        // Tambahkan
use App\Policies\UserPolicy;        // Tambahkan
use App\Policies\TransactionPolicy; // Tambahkan
use App\Policies\RequestPolicy;     // Tambahkan
use App\Policies\AcquisitionPolicy; // Tambahkan
use Illuminate\Support\Facades\Gate; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Correct way to register a view component:
        Blade::component('app-layout', 'layouts.app');
        Blade::component('application-logo', 'components.application-logo');
        Blade::component('nav-link', 'components.nav-link');
        Blade::component('dropdown', 'components.dropdown');
        Blade::component('dropdown-link', 'components.dropdown-link');
        Blade::component('responsive-nav-link', 'components.responsive-nav-link');

        Inventaris::observe(InventarisObserver::class);
        Gate::policy(Inventaris::class, InventarisPolicy::class); // Daftarkan policy
        Gate::policy(Room::class, RoomPolicy::class);
        Gate::policy(Unit::class, UnitPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(Request::class, RequestPolicy::class);
        Gate::policy(Acquisition::class, AcquisitionPolicy::class);
    }
}
