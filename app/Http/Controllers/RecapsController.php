<?php

namespace App\Http\Controllers;
use App\Models\TypeAlert;
use App\Models\EmailTemplate;
use App\Models\Report;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class RecapsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    try {
      $reports = Report::with('user')->get();
      return view('content.organisation.reports.indexReport', ['reports' => $reports]);
    } catch (Exception $ex) {
      Log::error('Error fetching reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  /**
   * Display a listing of the resource for the organisation.
   *
   * @return \Illuminate\View\View
   */
  public function indexOrganisation()
  {
    try {
      $user = session("user");
      $reports = Report::where("user_id", $user->id)->with('user')->get();
      return view('content.organisation.reports.indexReport', ['reports' => $reports]);
    } catch (Exception $ex) {
      Log::error('Error fetching organisation reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\View\View
   */
  public function create(Request $request)
  {
    try {
      $user = session("user");
      if (!$user) {
        return redirect()->route('login')->with('error', 'Session expired.');
      }

      $type_alerts = TypeAlert::all();     // récupération des types
      $templates = EmailTemplate::all();
      return view('content.organisation.reports.createReport', compact('type_alerts', 'templates'));
    } catch (Exception $ex) {
      Log::error('Error in create reports form: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', 'Could not load the reports creation form: ' . $ex->getMessage());
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    try {
      $validated = $request->validate([
        'date' => 'required|date_format:Y-m-d\TH:i',
        'total_orders' => 'required|integer|min:0',
        'total_revenue' => 'required|numeric|min:0',
        'average_sales' => 'required|numeric|min:0',
        'total_quantities' => 'required|integer|min:0',
        'total_clients' => 'required|integer|min:0',
      ]);

      $user = session("user");
      if (!$user) {
        return redirect()->route('login')->with('error', 'Session expired. Please login again.');
      }

      $data = [
        'user_id' => $user->id,
        'date' => \Carbon\Carbon::parse($validated['date'])->format('Y-m-d H:i:s'),
        'total_orders' => $validated['total_orders'],
        'total_revenue' => $validated['total_revenue'],
        'average_sales' => $validated['average_sales'],
        'total_quantities' => $validated['total_quantities'],
        'total_clients' => $validated['total_clients'],
      ];

      $report = Report::create($data);

      if ($report) {
        return redirect()->route('organisation-reports')->with('success', 'Report created successfully.');
      } else {
        return redirect()->back()->with('error', 'Failed to create reports.')->withInput();
      }
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('Validation error in store reports: ', $e->errors());
      return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      Log::error('Error in store reports: ' . $ex->getMessage());
      return redirect()->back()->with('error', 'An unexpected error occurred: ' . $ex->getMessage())->withInput();
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\View\View
   */
  public function show($id)
  {
    try {
      $report = Report::findOrFail($id);
      return view('content.organisation.reports.show', compact('report'));
    } catch (Exception $ex) {
      Log::error('Error showing reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\View\View
   */
  public function update($id)
  {
    try {
      $report = Report::findOrFail($id);
      return view('content.organisation.reports.edit', compact('report'));
    } catch (Exception $ex) {
      Log::error('Error in update reports form: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function edit(Request $request, $id)
  {
    try {
      $report = Report::findOrFail($id);

      $validated = $request->validate([
        'date' => 'required|date_format:Y-m-d\TH:i',
        'total_orders' => 'required|integer|min:0',
        'total_revenue' => 'required|numeric|min:0',
        'average_sales' => 'required|numeric|min:0',
        'total_quantities' => 'required|integer|min:0',
        'total_clients' => 'required|integer|min:0',
      ]);

      $report->update([
        'date' => \Carbon\Carbon::parse($validated['date'])->format('Y-m-d H:i:s'),
        'total_orders' => $validated['total_orders'],
        'total_revenue' => $validated['total_re Venue'],
        'average_sales' => $validated['average_sales'],
        'total_quantities' => $validated['total_quantities'],
        'total_clients' => $validated['total_clients'],
      ]);

      return redirect()->route('organisation-reports')->with('success', 'Report updated successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('Validation error in edit reports: ', $e->errors());
      return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      Log::error('Error in edit reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy($id)
  {
    try {
      $report = Report::findOrFail($id);
      $report->delete();
      return redirect()->route('organisation-reports')->with('success', 'Report deleted successfully.');
    } catch (Exception $ex) {
      Log::error('Error deleting reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  public function generateForm()
  {
    return view('content.organisation.reports.generate');
  }
}
?>
