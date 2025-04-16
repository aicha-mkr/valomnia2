<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\employee;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
  /**
   * Display a listing of the check-ins.
   */
  public function index()
  {
    $id = 1; // You can retrieve this dynamically, e.g., from auth
    $user = User::find($id);

    return employee::ListCheckIns(array(
      "user_id" => $id,
      "organisation" => $user->organisation,
      "cookies" => "FCD1CE705B01E90DFC76270189A6D00E", // You can retrieve the actual cookies dynamically here
      "offset" => 0, // Optionally, get this from request
      "max" => 100,    // Optionally, get this from request
      "filters" => [
        // Optionally, add any filters from request here
      ]
    ));
  }

  // The other RESTful methods (create, store, show, edit, update, destroy) can be added as needed
}
