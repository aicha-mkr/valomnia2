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
use Illuminate\Support\Facades\Validator; // Added for custom validation

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
      return view("content.organisation.alerts.index", ["alerts" => $alerts]);
    } catch (Exception $ex) {
      return redirect()->route("alerts-list")->with("error", $ex->getMessage());
    }
  }

  public function indexOrganisation()
  {
    try {
      $user = session("user");
      $alerts = Alert::where("user_id", $user->id)->with(["type"])->get();
      return view("content.organisation.alerts.index", ["alerts" => $alerts]);
    } catch (Exception $ex) {
      return redirect()->route("alerts-list")->with("error", $ex->getMessage());
    }
  }

  public function store(Request $request)
  {
    try {
      $baseRules = [
        "title" => "required|string|max:255",
        "type_id" => "required|exists:type_alerts,id",
        "template_id" => "required|exists:email_templates,id",
        "description" => "required|string",
        "users_email" => "required|string", // Assuming this is the Tagify input
        "status" => "sometimes|boolean",
        "every_day" => "sometimes|boolean",
        "time" => "required_if:every_day,true|nullable|date_format:H:i",
        "date" => "required_if:every_day,false|nullable|date",
        // General params validation (array)
        "params" => "nullable|array",
      ];

      // Dynamically add rules based on alert type
      $alertType = TypeAlert::find($request->input("type_id"));
      $specificRules = [];

      if ($alertType) {
        if ($alertType->slug === "expired-stock") {
          $specificRules["warehouse_ids"] = "nullable|array";
          $specificRules["warehouse_ids.*"] = "nullable|numeric";
          $specificRules["quantity"] = "nullable|numeric|min:1";
        } elseif ($alertType->slug === "checkin-out-of-hours") {
          $specificRules["employee"] = "nullable|string"; // This comes from the main request, not params array
        } elseif ($alertType->slug === "vente-seuil-depasse-pdv") {
          $specificRules["params.customer_reference"] = "required|string|max:255";
          $specificRules["params.responsable_email"] = "required|email|max:255";
          $specificRules["params.periode_moyenne_jours"] = "required|integer|min:1";
          $specificRules["params.seuil_pourcentage"] = "required|integer|min:1";
        }
      }

      $validator = Validator::make($request->all(), array_merge($baseRules, $specificRules));

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
      $validated = $validator->validated();

      $user = session("user");
      if (!$user) {
        return redirect()->route("login")->with("error", "Session expired. Please login again.");
      }

      $data = [
        "title" => $validated["title"],
        "type_id" => $validated["type_id"],
        "template_id" => $validated["template_id"],
        "description" => $validated["description"],
        "users_email" => $validated["users_email"],
        "user_id" => $user->id,
        "status" => $request->boolean("status", true),
        "every_day" => $request->boolean("every_day", false),
        "time" => $validated["time"] ?? null,
        "date" => $validated["date"] ?? null,
        "quantity" => $validated["quantity"] ?? null, // Ensure quantity is handled
      ];

      $parameters = [];
      if ($alertType) {
        if ($alertType->slug === "expired-stock" && isset($validated["warehouse_ids"])) {
          $warehousesResponse = Warehouse::ListWarhouses([
            "user_id" => $user->id,
            "organisation" => $user->organisation,
            "cookies" => $user->cookies
          ]);
          if (isset($warehousesResponse["data"])) {
            $selectedWarehouses = collect($warehousesResponse["data"])
              ->whereIn("id", $validated["warehouse_ids"])
              ->pluck("reference")
              ->toArray();
            $parameters["warehouse_refs"] = $selectedWarehouses;
          }
        } elseif ($alertType->slug === "checkin-out-of-hours" && isset($validated["employee"])) {
          $parameters["employee_ref"] = $validated["employee"];
        } elseif ($alertType->slug === "vente-seuil-depasse-pdv" && isset($validated["params"])) {
          $parameters["customer_reference"] = $validated["params"]["customer_reference"];
          $parameters["responsable_email"] = $validated["params"]["responsable_email"];
          $parameters["periode_moyenne_jours"] = $validated["params"]["periode_moyenne_jours"];
          $parameters["seuil_pourcentage"] = $validated["params"]["seuil_pourcentage"];
        }
      }

      $data["parameters"] = json_encode($parameters);

      $alert = Alert::create($data);

      if ($alert) {
        AlertHistory::create([
          "alert_id" => $alert->id,
          "iduser" => $user->id,
          "attempts" => 0,
        ]);
        return redirect()->route("organisation-alerts")->with("success", "Alert created successfully.");
      } else {
        return redirect()->back()->with("error", "Failed to create alert.")->withInput();
      }

    } catch (Exception $ex) {
      Log::error("Error storing alert: " . $ex->getMessage() . "\n" . $ex->getTraceAsString());
      return redirect()->back()->with("error", "An unexpected error occurred: " . $ex->getMessage())->withInput();
    }
  }

  public function create(Request $request)
  {
    try {
      $has_error = false;
      $user = session("user");
      if (!$user) { return redirect()->route("login")->with("error", "Session expired."); }

      $type_alerts = TypeAlert::where("status", 1)->get(["id", "name", "slug"]);
      $warhouses_response = Warehouse::ListWarhouses(["user_id" => $user->id,"organisation" => $user->organisation,"cookies" => $user->cookies]);
      $warhouses = isset($warhouses_response["data"]) ? $warhouses_response["data"] : [];
      if (isset($warhouses_response["error"])) { $has_error = true; Log::error("Error fetching warehouses", (array)$warhouses_response["error"]);}

      $templates = EmailTemplate::all(); // Corrected: Removed where("status", 1)

      $employees_response = \App\Models\Employee::ListEmployees(["user_id" => $user->id,"organisation" => $user->organisation,"cookies" => $user->cookies]);
      $employees = isset($employees_response["data"]) ? $employees_response["data"] : [];
      if (isset($employees_response["error"])) { $has_error = true; Log::error("Error fetching employees", (array)$employees_response["error"]); }

      return view("content.organisation.alerts.create", compact(
        "type_alerts",
        "templates",
        "warhouses",
        "employees",
        "has_error"
      ));
    } catch (Exception $ex) {
      Log::error("Error in create alert form: " . $ex->getMessage());
      return redirect()->route("organisation-alerts")->with("error", "Could not load the alert creation form: " . $ex->getMessage());
    }
  }

  public function show($id)
  {
    $alert = Alert::with(["type", "user", "template"])->findOrFail($id);
    $params = json_decode($alert->parameters, true);
    return view("content.organisation.alerts.show", compact("alert", "params"));
  }

  public function edit(Request $request, $id) // This is actually the update logic in your routes
  {
    try {
      $alert = Alert::findOrFail($id);

      $baseRules = [
        "title" => "required|string|max:255",
        "type_id" => "required|exists:type_alerts,id",
        "template_id" => "required|exists:email_templates,id",
        "description" => "required|string",
        "users_email" => "required|string",
        "status" => "sometimes|boolean",
        "every_day" => "sometimes|boolean",
        "time" => "required_if:every_day,true|nullable|date_format:H:i",
        "date" => "required_if:every_day,false|nullable|date",
        "params" => "nullable|array",
      ];

      $alertType = TypeAlert::find($request->input("type_id"));
      $specificRules = [];

      if ($alertType) {
        if ($alertType->slug === "expired-stock") {
          $specificRules["warehouse_ids"] = "nullable|array";
          $specificRules["warehouse_ids.*"] = "nullable|numeric";
          $specificRules["quantity"] = "nullable|numeric|min:1";
        } elseif ($alertType->slug === "checkin-out-of-hours") {
          $specificRules["employee"] = "nullable|string";
        } elseif ($alertType->slug === "vente-seuil-depasse-pdv") {
          $specificRules["params.customer_reference"] = "required|string|max:255";
          $specificRules["params.responsable_email"] = "required|email|max:255";
          $specificRules["params.periode_moyenne_jours"] = "required|integer|min:1";
          $specificRules["params.seuil_pourcentage"] = "required|integer|min:1";
        }
      }

      $validator = Validator::make($request->all(), array_merge($baseRules, $specificRules));

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
      $validated = $validator->validated();

      $user = session("user");
      if (!$user) {
        return redirect()->route("login")->with("error", "Session expired. Please login again.");
      }

      $alert->title = $validated["title"];
      $alert->type_id = $validated["type_id"];
      $alert->template_id = $validated["template_id"];
      $alert->description = $validated["description"];
      $alert->users_email = $validated["users_email"];
      $alert->status = $request->boolean("status", false); // Default to false if not present
      $alert->every_day = $request->boolean("every_day", false);
      $alert->time = $validated["time"] ?? null;
      $alert->date = $validated["date"] ?? null;
      $alert->quantity = $validated["quantity"] ?? null;

      $parameters = [];
      if ($alertType) {
        if ($alertType->slug === "expired-stock" && isset($validated["warehouse_ids"])) {
          $warehousesResponse = Warehouse::ListWarhouses([
            "user_id" => $user->id,
            "organisation" => $user->organisation,
            "cookies" => $user->cookies
          ]);
          if (isset($warehousesResponse["data"])) {
            $selectedWarehouses = collect($warehousesResponse["data"])
              ->whereIn("id", $validated["warehouse_ids"])
              ->pluck("reference")
              ->toArray();
            $parameters["warehouse_refs"] = $selectedWarehouses;
          }
        } elseif ($alertType->slug === "checkin-out-of-hours" && isset($validated["employee"])) {
          $parameters["employee_ref"] = $validated["employee"];
        } elseif ($alertType->slug === "vente-seuil-depasse-pdv" && isset($validated["params"])) {
          $parameters["customer_reference"] = $validated["params"]["customer_reference"];
          $parameters["responsable_email"] = $validated["params"]["responsable_email"];
          $parameters["periode_moyenne_jours"] = $validated["params"]["periode_moyenne_jours"];
          $parameters["seuil_pourcentage"] = $validated["params"]["seuil_pourcentage"];
        }
      }
      $alert->parameters = json_encode($parameters);

      $alert->save();
      return redirect()->route("organisation-alerts")->with("success", "Alert updated successfully.");

    } catch (Exception $ex) {
      Log::error("Error updating alert: " . $ex->getMessage() . "\n" . $ex->getTraceAsString());
      return redirect()->route("organisation-alerts")->with("error", $ex->getMessage());
    }
  }

  public function update($id) // This is the show edit form method in your routes
  {
    try {
      $alert = Alert::findOrFail($id);
      $user = session("user");
      if (!$user) { return redirect()->route("login")->with("error", "Session expired."); }

      $type_alerts = TypeAlert::where("status", 1)->get(["id", "name", "slug"]);
      $templates = EmailTemplate::all(); // Corrected: Removed where("status", 1)

      $warhouses_response = Warehouse::ListWarhouses(["user_id" => $user->id, "organisation" => $user->organisation, "cookies" => $user->cookies]);
      $warhouses = isset($warhouses_response["data"]) ? $warhouses_response["data"] : [];

      $employees_response = \App\Models\Employee::ListEmployees(["user_id" => $user->id,"organisation" => $user->organisation,"cookies" => $user->cookies]);
      $employees = isset($employees_response["data"]) ? $employees_response["data"] : [];

      // Decode parameters for the view
      $alert_params = json_decode($alert->parameters, true) ?? [];

      return view("content.organisation.alerts.edit", compact("alert", "type_alerts", "templates", "warhouses", "employees", "alert_params"));

    } catch (Exception $ex) {
      Log::error("Error in update alert form: " . $ex->getMessage());
      return redirect()->route("organisation-alerts")->with("error", $ex->getMessage());
    }
  }

  public function destroy($id)
  {
    try {
      $alert = Alert::findOrFail($id);
      if (isset($alert)) {
        AlertHistory::where("alert_id", $alert->id)->delete();
        $alert->delete();
        return redirect()->route("organisation-alerts")->with("success", "Alert deleted successfully.");
      } else {
        return redirect()->route("organisation-alerts")->with("error", "Alert not found");
      }
    } catch (Exception $ex) {
      return redirect()->route("organisation-alerts")->with("error", $ex->getMessage());
    }
  }
}

