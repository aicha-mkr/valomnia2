<?php

namespace App\Http\Controllers;
use App\Models\Utils;
use App\Models\TypeAlert;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
class TypeAlertController extends Controller
{
    public function index()
    {
        $typeAlerts = TypeAlert::all();
         return  view('content.admin.alerts.types.index',['typeAlerts'=>$typeAlerts]);
    }
    public function create(Request $request)
    {

          return  view('content.admin.alerts.types.create');

    }
    public function store(Request $request)
    {
        try{
                $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                    'status' => 'required|in:1,0',// Ensure status is one of these values
                ]);

                $data=$request->all();
                $slug=Utils::createSlug($data["name"]);
                $data["slug"]=$slug;
                // Créer un nouveau TypeAlert
                $typeAlert = TypeAlert::create($data);
                 return redirect()->route('alerts-types')->with('success', 'Alert type created successfully.');
         }catch (Exception $ex){
             return redirect()->route('alerts-types')->with('error', $ex->getMessage());
         }


    }

    public function show($id)
    {
        $typeAlert = TypeAlert::findOrFail($id);
         return  view('admin.alerts.types.show',compact('typeAlert'));
    }


   public function edit($id)
   {
       try {
           // Retrieve the alert by its id
           $type_alert = TypeAlert::findOrFail($id);
           // Return the edit view with the alert and type_alerts data
           return view('content.admin.alerts.types.edit', compact('type_alert'));
       } catch (Exception $ex) {
           // Redirect back with an error message if there's an exception
           return redirect()->route('alerts-types')->with('error', $ex->getMessage());
       }
   }

   public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255',
                'type_id' => 'required|exists:type_alerts,id',
                'description' => 'required|string|max:255',
                'status' => 'required|boolean',
                'date' => 'nullable|date',
                'time' => 'nullable|date_format:H:i'
            ]);

            // Retrieve the alert by its id
            $type_alert = TypeAlert::findOrFail($id);

            // Prepare the data for updating
            $data = $request->all();
            $data['slug'] = Utils::createSlug($data['name']);

            // Update the alert with the new data
            $type_alert->update($data);

            // Redirect back with a success message
            return redirect()->route('alerts-types')->with('success', 'Type Alert updated successfully.');
        } catch (Exception $ex) {
            // Redirect back with an error message if there's an exception
            return redirect()->route('alerts-types')->with('error', $ex->getMessage());
        }
    }






    public function destroy($id)
    {
        try {
        $typeAlert = TypeAlert::findOrFail($id);
        $typeAlert->delete();
            return redirect()->route('alerts-types')->with('success', 'Type Alert deleted successfully.');
        } catch (Exception $ex) {
            // Redirect back with an error message if there's an exception
            return redirect()->route('alerts-types')->with('error', $ex->getMessage());
        }
    }
}
