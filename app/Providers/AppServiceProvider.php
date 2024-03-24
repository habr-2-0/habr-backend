<?php

namespace App\Providers;

use App\Contracts\ICommentRepository;
use App\Contracts\IFollowRepository;
use App\Contracts\IPostRepository;
use App\Contracts\IUserRepository;
use App\Repositories\CommentRepository;
use App\Repositories\FollowRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ICommentRepository::class, CommentRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPostRepository::class, PostRepository::class);
        $this->app->bind(IFollowRepository::class, FollowRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
