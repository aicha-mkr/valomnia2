<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
      $request = app('request');
      $currentUrl = $request->url();


      // Get path portion of URL
      $currentPath = $request->path();
      if(strpos($currentPath, "admin/") !== false) {
          $path_file='resources/menu/verticalMenu.json';

      }else{
          $path_file='resources/menu/verticalMenuOrganisation.json';
      }
      //echo $path_file;die();
      $verticalMenuJson=file_get_contents(base_path($path_file));
    $verticalMenuData = json_decode($verticalMenuJson);
    //echo json_encode($verticalMenuData);die();
    // Share all menuData to all the views
    \View::share('menuData', [$verticalMenuData]);
  }
}