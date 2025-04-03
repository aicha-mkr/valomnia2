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
        $user = session("user");

        // Collect and process request data
      $data['quantity'] = $request->input('quantity', null); // Default to null if not provided

      $data = $request->all();
        $data["user_id"] = $user->id;
        $data["status"] = $request->get("status") ? 1 : 0;
        $data["every_day"] = $request->get("every_day") ? 1 : 0;

        if ($request->boolean('every_day')) {
            $validatedData["time"] = $request->input("time"); // Register only the time
            $validatedData["date"] = null; // Nullify date
        } else {
            $validatedData["date"] = $request->input("date"); // Register the date
            $validatedData["time"] = $request->input("time"); // Register the time
        }

        // Handle warehouse_ids as a comma-separated string (optional)
        $data["warehouse_ids"] = $request->has("warehouse_ids")
            ? ',' . implode(',', $request->get("warehouse_ids")) . ','
            : null;

        // Create the alert in the database
        $alert = Alert::create($data);

        if ($alert) {
            // Log the creation in the AlertHistory table
            AlertHistory::create([
                'alert_id' => $alert->id,
                'iduser' => $user->id,
                'attempts' => 0,
            ]);

            return redirect()->route('organisation-alerts')->with('success', 'Alert created successfully.');
        } else {
            return redirect()->route('organisation-alerts')->with('error', 'Failed to create alert.');
        }
    } catch (Exception $ex) {
        return redirect()->route('organisation-alerts')->with('error', $ex->getMessage());
    }
}

  public function create(Request $request)
  {
    try {
      $has_error = false;
      $user = session("user");

      // Fetch active alert types
      $type_alerts = TypeAlert::where("status", 1)->get(["id", "name", "slug"]);

      // Fetch warehouse data
      $warhouses_response = Warehouse::ListWarhouses([
        "user_id" => $user->id,
        "organisation" => $user->organisation,
        "cookies" => $user->cookies,
      ]);

      // Initialize warehouses
      $warhouses = isset($warhouses_response["data"]) ? $warhouses_response["data"] : [];

      // Check for errors in the warehouse response
      if (isset($warhouses_response["error"])) {
        $has_error = true;
      }

      // Fetch all templates for the dropdown
      $templates = EmailTemplate::all();

      // Pass data to the view
      return view('content.organisation.alerts.create', compact('type_alerts', 'warhouses', 'templates', 'has_error'));
    } catch (Exception $ex) {
      // Handle exceptions
      return redirect()->route('organisation-alerts')->with('error', $ex->getMessage());
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
