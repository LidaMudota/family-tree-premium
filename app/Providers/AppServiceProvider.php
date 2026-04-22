<?php

namespace App\Providers;

use App\Models\Tree;
use App\Policies\TreePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(Tree::class, TreePolicy::class);

        RateLimiter::for('auth', fn (Request $request) => Limit::perMinute(5)->by($request->ip().'|'.$request->input('email')));
        RateLimiter::for('search', fn (Request $request) => Limit::perMinute(60)->by((string) optional($request->user())->id ?: $request->ip()));
        RateLimiter::for('export', fn (Request $request) => Limit::perMinute(12)->by((string) optional($request->user())->id ?: $request->ip()));
        RateLimiter::for('upload', fn (Request $request) => Limit::perMinute(20)->by((string) optional($request->user())->id ?: $request->ip()));
    }
}
