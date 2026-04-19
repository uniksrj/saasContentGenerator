<?php

namespace App\Providers;

use App\Models\Project;
use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->singleton(ImageProcessingService::class, function ($app) {
      return  new ImageProcessingService();
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    View::composer('saas.*', function ($view): void {
      $user = Auth::user();
      if ($user === null) {
        return;
      }

      $projects = Project::query()
        ->ownedBy($user->id)
        ->orderBy('name')
        ->get(['id', 'name', 'slug']);

      $currentProject = null;
      if ($user->current_project_id !== null) {
        $currentProject = $projects->firstWhere('id', $user->current_project_id);
      }

      if ($currentProject === null && $projects->isNotEmpty()) {
        $currentProject = $projects->first();
      }

      $view->with('navProjects', $projects);
      $view->with('navCurrentProject', $currentProject);
    });
  }
}
