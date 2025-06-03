<?php

namespace App\Http\Controllers;

use App\Models\TypeAlert;
use App\Models\EmailTemplate;
use App\Models\Report;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RecapsController extends Controller
{
  public function index()
  {
    try {
      $reports = Report::with('user')->get();
      return view('content.organisation.reports.indexReport', compact('reports'));
    } catch (Exception $ex) {
      Log::error('Error fetching reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  public function indexOrganisation()
  {
    try {
      $user = Session::get("user");
      if (!$user) {
        return redirect()->route('login')->with('error', 'Session expired.');
      }
      $reports = Report::where("user_id", $user->id)->with('user')->get();
      return view('content.organisation.reports.indexReport', compact('reports'));
    } catch (Exception $ex) {
      Log::error('Error fetching organisation reports: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', $ex->getMessage());
    }
  }

  public function create(Request $request)
  {
    try {
      $user = Session::get("user");
      if (!$user) {
        return redirect()->route('login')->with('error', 'Session expired.');
      }
      $type_alerts = TypeAlert::all();
      $templates = EmailTemplate::all();
      return view('content.organisation.reports.createReport', compact('type_alerts', 'templates'));
    } catch (Exception $ex) {
      Log::error('Error loading create report form: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')->with('error', 'Could not load the form: ' . $ex->getMessage());
    }
  }

  public function store(Request $request)
  {
    try {
      Log::info('Store method called', $request->all());

      $validated = $request->validate([
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'fields' => [
          'required',
          'array',
          'min:1',
          'in:total_orders,total_revenue,average_sales,total_quantities,total_clients,top_selling_items'
        ],
        'users_email' => 'required',
        'schedule' => 'required|in:none,daily,weekly,monthly',
        'status' => 'nullable|in:on,1,0',
        'time' => 'nullable|date_format:H:i',
      ]);

      $user = Session::get("user");
      if (!$user) {
        Log::error('Session user not found');
        return redirect()->route('login')->with('error', 'Session expired.');
      }

      // Parse users_email if JSON
      $usersEmail = $validated['users_email'];
      if (is_string($usersEmail) && json_decode($usersEmail, true)) {
        $emails = array_column(json_decode($usersEmail, true), 'value');
        $usersEmail = implode(',', $emails);
      }

      // Prepare data
      $data = [
        'user_id' => $user->id,
        'startDate' => $validated['startDate'],
        'endDate' => $validated['endDate'],
        'users_email' => $usersEmail,
        'schedule' => $validated['schedule'] ?? 'none',
        'time' => $validated['time'] ?? null,
        'status' => $request->has('status') ? true : false,
        'date' => now(),
        'total_orders' => null,
        'total_revenue' => null,
        'average_sales' => null,
        'total_quantities' => null,
        'total_clients' => null,
        'top_selling_items' => null,
      ];

      foreach ($validated['fields'] as $field) {
        $data[$field] = $field === 'top_selling_items' ? '' : 1;
      }

      // Create report
      $report = Report::create($data);

      if (!$report) {
        Log::error('Report creation failed', $data);
        return back()->withInput()->with('error', 'Failed to save report configuration.');
      }

      return redirect()->route('organisation-reports')->with('success', 'Report configured successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('Validation failed', ['errors' => $e->errors()]);
      return back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      Log::error('Error saving report: ' . $ex->getMessage(), ['data' => $request->all()]);
      return back()->withInput()->with('error', 'An error occurred: ' . $ex->getMessage());
    }
  }

  public function updateForm($id)
  {
    try {
      $report = Report::findOrFail($id);

      // Build the kpis array
      $kpis = [];
      if ($report->total_orders !== null) $kpis[] = 'total_orders';
      if ($report->total_revenue !== null) $kpis[] = 'total_revenue';
      if ($report->average_sales !== null) $kpis[] = 'average_sales';
      if ($report->total_quantities !== null) $kpis[] = 'total_quantities';
      if ($report->total_clients !== null) $kpis[] = 'total_clients';
      if ($report->top_selling_items !== null) $kpis[] = 'top_selling_items';

      // Add kpis array to the report object
      $report->kpis = $kpis;

      $type_alerts = TypeAlert::all();
      $templates = EmailTemplate::all();

      return view('content.organisation.reports.edit', compact('report', 'type_alerts', 'templates'));
    } catch (Exception $ex) {
      return redirect()->route('organisation-reports')
        ->with('error', 'Could not load the form: ' . $ex->getMessage());
    }
  }

  public function edit(Request $request, $id)
  {
    try {
      $validated = $request->validate([
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'fields' => ['required', 'array', 'min:1', 'in:total_orders,total_revenue,average_sales,total_quantities,total_clients,top_selling_items'],
        'users_email' => 'required|string',
        'schedule' => 'required|in:none,daily,weekly,monthly',
        'status' => 'boolean',
        'time' => 'nullable|date_format:H:i',
      ]);

      $report = Report::findOrFail($id);
      $data = [
        'startDate' => $validated['startDate'],
        'endDate' => $validated['endDate'],
        'users_email' => $validated['users_email'],
        'schedule' => $validated['schedule'],
        'time' => $validated['time'],
        'status' => $request->boolean('status', true),
        'total_orders' => null,
        'total_revenue' => null,
        'average_sales' => null,
        'total_quantities' => null,
        'total_clients' => null,
        'top_selling_items' => null,
      ];

      foreach ($validated['fields'] as $field) {
        $data[$field] = $field === 'top_selling_items' ? '' : 1;
      }

      $report->update($data);
      return redirect()->route('organisation-reports')->with('success', 'Report updated successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
      return back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      return back()->withInput()->with('error', 'Error updating report: ' . $ex->getMessage());
    }
  }

  public function destroy($id)
  {
    try {
      $report = Report::findOrFail($id);
      $report->delete();
      return redirect()->route('organisation-reports')->with('success', 'Report deleted successfully.');
    } catch (Exception $ex) {
      return redirect()->route('organisation-reports')->with('error', 'Error deleting report: ' . $ex->getMessage());
    }
  }
  public function show($id)
  {
    try {
      $report = Report::findOrFail($id);

      // Build the kpis array
      $kpis = [];
      if ($report->total_orders !== null) $kpis[] = 'total_orders';
      if ($report->total_revenue !== null) $kpis[] = 'total_revenue';
      if ($report->average_sales !== null) $kpis[] = 'average_sales';
      if ($report->total_quantities !== null) $kpis[] = 'total_quantities';
      if ($report->total_clients !== null) $kpis[] = 'total_clients';
      if ($report->top_selling_items !== null) $kpis[] = 'top_selling_items';

      return view('content.organisation.reports.show', compact('report', 'kpis'));
    } catch (Exception $ex) {
      Log::error('Error fetching report: ' . $ex->getMessage());
      return redirect()->route('organisation-reports')
        ->with('error', 'Could not load report: ' . $ex->getMessage());
    }
  }


  public function generateForm()
  {
    return view('content.organisation.reports.generate');
  }

  public function generateReport(Request $request)
  {
    try {
      // Placeholder implementation - replace with actual logic
      Log::info('Generate report called', $request->all());
      return redirect()->route('organisation-reports')->with('success', 'Report generated successfully.');
    } catch (Exception $ex) {
      Log::error('Error generating report: ' . $ex->getMessage());
      return back()->with('error', 'Error generating report: ' . $ex->getMessage());
    }
  }
}
