<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\TypeAlert;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\AlertHistory;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\Employee;
use App\Http\Resources\AlertResource;
use App\Jobs\AlertStock;
use App\Http\Requests\StoreAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AlertController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return AnonymousResourceCollection
   */
  public function index()
  {
    try {
      $alerts = Alert::with(["type", "user"])->get();
      return view('content.organisation.alerts.index', ['alerts' => $alerts]);
    } catch (Exception $ex) {
      return redirect()->route('alerts-list')->with('error', $ex->getMessage());
    }
  }

  public function indexOrganisation()
  {

    try {
      $user = session("user");
      $alerts = Alert::where("user_id", $user->id)->with(["type"])->get();
      //echo json_encode($alerts);die();
      return view('content.organisation.alerts.index', ['alerts' => $alerts]);
    } catch (Exception $ex) {
      return redirect()->route('alerts-list')->with('error', $ex->getMessage());
    }
  }

  public function store(Request $request)
  {
    try {
      $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type_id' => 'required|exists:type_alerts,id',
        'quantity' => 'nullable|numeric',
        'template_id' => 'required|exists:email_templates,id',
        'description' => 'required|string',
        'users_email' => 'required|string',
        'status' => 'sometimes|boolean',
        'every_day' => 'sometimes|boolean',
        'time' => 'required_if:every_day,true|nullable|date_format:H:i',
        'date' => 'required_if:every_day,false|nullable|date',
        'warehouse_ids' => 'nullable|array',
        'warehouse_ids.*' => 'nullable|numeric',
        'employee' => 'nullable|string',
      ]);

      $user = session("user");
      if (!$user) {
        return redirect()->route('login')->with('error', 'Session expired. Please login again.');
      }

      // Récupérer le type d'alerte
      $alertType = TypeAlert::findOrFail($validated['type_id']);

      // Préparer les données pour l'insertion
      $data = [
        'title' => $validated['title'],
        'type_id' => $validated['type_id'],
        'quantity' => $validated['quantity'],
        'template_id' => $validated['template_id'],
        'description' => $validated['description'],
        'users_email' => $validated['users_email'],
        'user_id' => $user->id,
        'status' => $request->boolean("status", true),
        'every_day' => $request->boolean("every_day", false),
        'time' => $validated['time'],
        'date' => $validated['date'],
      ];

      // Gérer les paramètres selon le type d'alerte
      $parameters = [];

      if ($alertType->slug === 'expired-stock' && isset($validated['warehouse_ids'])) {
        $warehousesResponse = Warehouse::ListWarhouses([
          'user_id' => $user->id,
          'organisation' => $user->organisation,
          'cookies' => $user->cookies
        ]);

        if (isset($warehousesResponse['data'])) {
          $selectedWarehouses = collect($warehousesResponse['data'])
            ->whereIn('id', $validated['warehouse_ids'])
            ->pluck('reference')
            ->toArray();

          $parameters['warehouse_refs'] = $selectedWarehouses;
        }
      } elseif ($alertType->slug === 'check-in-hors-heures' && isset($validated['employee'])) {
        $parameters['employee_ref'] = $validated['employee'];
      }

      // Stocker les paramètres dans la colonne parameters
      $data['parameters'] = json_encode($parameters);

      // Créer l'alerte
      $alert = Alert::create($data);

      if ($alert) {
        // Créer l'historique
        AlertHistory::create([
          'alert_id' => $alert->id,
          'iduser' => $user->id,
          'attempts' => 0,
        ]);

        return redirect()->route('organisation-alerts')->with('success', 'Alert created successfully.');
      } else {
        return redirect()->back()->with('error', 'Failed to create alert.')->withInput();
      }

    } catch (\Illuminate\Validation\ValidationException $e) {
      return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      return redirect()->back()->with('error', 'An unexpected error occurred: ' . $ex->getMessage())->withInput();
    }
  }

  public function create(Request $request)
  {
    try {
      $has_error = false;
      $user = session("user");
      if (!$user) {
        \Log::error('No user in session');
        return redirect()->route('login')->with('error', 'Session expired.');
      }

      \Log::info('Fetching alert types for user ID: ' . $user->id);
      $type_alerts = TypeAlert::where("status", 1)->get(["id", "name", "slug"]);

      \Log::info('Fetching warehouses for user ID: ' . $user->id);
      $warhouses_response = Warehouse::ListWarhouses([
        'user_id' => $user->id,
        'organisation' => $user->organisation,
        'cookies' => $user->cookies
      ]);
      $warhouses = isset($warhouses_response["data"]) ? $warhouses_response["data"] : [];
      if (isset($warhouses_response["error"])) {
        $has_error = true;
        \Log::error('Error fetching warehouses: ' . json_encode($warhouses_response["error"]));
      }

      \Log::info('Fetching employees for user ID: ' . $user->id);
      $employees_response = Employee::ListEmployees([
        'user_id' => $user->id,
        'organisation' => $user->organisation,
        'cookies' => $user->cookies
      ]);
      $employees = isset($employees_response["data"]) ? $employees_response["data"] : [];
      if (isset($employees_response["error"])) {
        $has_error = true;
        \Log::error('Error fetching employees: ' . json_encode($employees_response["error"]));
      }

      \Log::info('Fetching templates for user ID: ' . $user->id);
      $templates = EmailTemplate::where('status', 1)->get();

      return view('content.organisation.alerts.create', compact(
        'type_alerts',
        'templates',
        'warhouses',
        'employees',
        'has_error'
      ));
    } catch (Exception $ex) {
      \Log::error('Error in create alert form: ' . $ex->getMessage(), ['stack' => $ex->getTraceAsString()]);
      return redirect()->route('organisation-alerts')->with('error', 'Could not load the alert creation form: ' . $ex->getMessage());
    }
  }
  public function show($id)
  {
    $alert = Alert::findOrFail($id);

    return view('content.organisation.alerts.show', compact('alert'));
  }

  public function edit(Request $request, $id)
  {
    try {
      $alert = Alert::findOrFail($id);
      if(isset($alert)){
        $alert->title = $request->get("title");
        $alert->status = $request->get("status") ? 1 : 0;
        $alert->every_day = $request->get("every_day") ? 1 : 0;
        $alert->warehouse_ids= $request->get("warehouse_ids") ? ','.implode(',',$request->get("warehouse_ids")).',' : null;
        $alert->save();
        return redirect()->route('organisation-alerts')->with('success', 'Alert updated successfully.');
      }else{
        return redirect()->route('organisation-alerts')->with('error', "alert not found");
      }

    } catch (Exception $ex) {
      return redirect()->route('organisation-alerts')->with('error', $ex->getMessage());


    }
  }

  public function update($id)
  {
    $type_alerts = TypeAlert::where("status", 1)->get(array("id", "name"));
    try {
      $alert = Alert::findOrFail($id);

      $user = session("user");
      $type_alerts = TypeAlert::where("status", 1)->get(array("id", "name","slug"));
      $warhouses_response = Warehouse::ListWarhouses(array("user_id" => $user->id, "organisation" => $user->organisation, "cookies" => $user->cookies));
      $warhouses = [];
      if (isset($warhouses_response["data"])) {
        $warhouses = $warhouses_response["data"];
      }
      return view('content.organisation.alerts.edit', compact('alert', 'type_alerts', 'warhouses'));

    } catch (Exception $ex) {
      return redirect()->route('organisation-alerts')->with('error', $ex->getMessage());
    }
  }

  public function destroy($id)
  {
    try {
      $alert = Alert::findOrFail($id);
      if (isset($alert)) {
        AlertHistory::where("alert_id", $alert->id)->delete();
        $alert->delete();
        return redirect()->route('organisation-alerts')->with('success', 'Alert  deleted successfully.');
      } else {
        return redirect()->route('organisation-alerts')->with('error', "Alert not found");
      }

    } catch (Exception $ex) {
      return redirect()->route('organisation-alerts')->with('error', $ex->getMessage());


    }
  }

  /**
   * Create an alert and dispatch a job.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function createAlert(Request $request)
  {
    $data = $request->validate([
      'title' => 'required|string|max:255',
      'type' => 'required|string|max:50',
      'quantity' => 'required|integer',
      'description' => 'required|string',
      'organisation' => 'required|string',
    ]);

    // Create the alert
    $alert = Alert::create([
      'user_id' => $request->user()->id, // Assuming user_id is the foreign key
      'title' => $data['title'],
      'type' => $data['type'],
      'organisation' => $data['organisation'],
      'quantity' => $data['quantity'],
      'description' => $data['description'],
      'date' => now(),
      'status' => 'active', // Default status
    ]);

    // Dispatch the job
    // AlertStock::dispatch($alert);

    return response()->json(['message' => 'Alert created and email sent.'], 200);
  }
}
