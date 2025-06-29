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
      
      // Get the latest report for this user to pre-fill the form
      $latestReport = \App\Models\Report::where('user_id', $user->id)
        ->where(function($query) {
          $query->whereNotNull('total_revenue')
                ->orWhereNotNull('total_orders')
                ->orWhereNotNull('total_clients');
        })
        ->orderBy('date', 'desc')
        ->first();
      
      // Build the kpis array from the latest report
      $kpis = [];
      $defaultData = [
        'startDate' => now()->subDays(7)->format('Y-m-d'),
        'endDate' => now()->format('Y-m-d'),
        'users_email' => '',
        'schedule' => 'weekly',
        'time' => '08:00',
        'status' => true
      ];
      
      if ($latestReport) {
        // Build kpis array based on what was selected in the latest report
        if ($latestReport->total_orders !== null) $kpis[] = 'total_orders';
        if ($latestReport->total_revenue !== null) $kpis[] = 'total_revenue';
        if ($latestReport->average_sales !== null) $kpis[] = 'average_sales';
        if ($latestReport->total_quantities !== null) $kpis[] = 'total_quantities';
        if ($latestReport->total_clients !== null) $kpis[] = 'total_clients';
        if ($latestReport->top_selling_items !== null) $kpis[] = 'top_selling_items';
        
        // Use data from the latest report
        $defaultData = [
          'startDate' => $latestReport->startDate ? $latestReport->startDate->format('Y-m-d') : now()->subDays(7)->format('Y-m-d'),
          'endDate' => $latestReport->endDate ? $latestReport->endDate->format('Y-m-d') : now()->format('Y-m-d'),
          'users_email' => $latestReport->users_email ?? '',
          'schedule' => $latestReport->schedule ?? 'weekly',
          'time' => $latestReport->time ? (is_string($latestReport->time) ? substr($latestReport->time, 0, 5) : $latestReport->time->format('H:i')) : '08:00',
          'status' => $latestReport->status ?? true
        ];
      }
      
      // If no KPIs were found, set some defaults
      if (empty($kpis)) {
        $kpis = ['total_orders', 'total_revenue'];
      }
      
      $type_alerts = TypeAlert::all();
      $templates = EmailTemplate::all();
      $reportTemplates = EmailTemplate::where('type', 'Rapport')->get();
      
      return view('content.organisation.reports.createReport', compact('type_alerts', 'templates', 'kpis', 'defaultData', 'reportTemplates'));
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
        'email_template_id' => 'required|exists:email_templates,id',
      ]);

      // Debug: log validated data
      \Log::info('Validated data for report creation', $validated);

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

      // Generate demo data based on selected KPIs and date range
      $demoData = $this->generateDemoDataForReport($validated['fields'], $validated['startDate'], $validated['endDate']);

      // Prepare data
      $data = [
        'user_id' => $user->id,
        'startDate' => $validated['startDate'],
        'endDate' => $validated['endDate'],
        'users_email' => $usersEmail,
        'schedule' => $validated['schedule'] ?? 'none',
        'time' => !empty($validated['time']) ? $validated['time'] : null,
        'status' => $request->has('status') ? true : false,
        'date' => now(),
        'total_orders' => $demoData['total_orders'] ?? null,
        'total_revenue' => $demoData['total_revenue'] ?? null,
        'average_sales' => $demoData['average_sales'] ?? null,
        'total_quantities' => $demoData['total_quantities'] ?? null,
        'total_clients' => $demoData['total_clients'] ?? null,
        'top_selling_items' => $demoData['top_selling_items'] ?? null,
        'template_id' => $validated['email_template_id'],
      ];
      // Debug: log data array
      \Log::info('Data array for report creation', $data);

      // Create report
      $report = Report::create($data);

      if (!$report) {
        Log::error('Report creation failed', $data);
        return back()->withInput()->with('error', 'Failed to save report configuration.');
      }

      // ENVOI IMMEDIAT SI "Send Now" SELECTIONNE
      if ($validated['schedule'] === 'none') {
        $emails = explode(',', $usersEmail);
        foreach ($emails as $email) {
          if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            dispatch(new \App\Jobs\SendReportEmail($report, trim($email)));
          }
        }
      }

      return redirect()->route('organisation-reports')->with('success', 'Report configured successfully with demo data.');
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('Validation failed', ['errors' => $e->errors()]);
      return back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      Log::error('Error saving report: ' . $ex->getMessage(), ['data' => $request->all()]);
      return back()->withInput()->with('error', 'An error occurred: ' . $ex->getMessage());
    }
  }

  private function generateDemoDataForReport($selectedKpis, $startDate, $endDate)
  {
    $demoData = [];
    
    // Calculate days difference for realistic data
    $start = \Carbon\Carbon::parse($startDate);
    $end = \Carbon\Carbon::parse($endDate);
    $daysDiff = $start->diffInDays($end) + 1;
    $weeksDiff = max(1, ceil($daysDiff / 7)); // Minimum 1 week
    
    // Generate realistic demo data in TND based on selected KPIs
    if (in_array('total_orders', $selectedKpis)) {
      $baseOrders = rand(80, 120); // Base orders per week
      $demoData['total_orders'] = $baseOrders * $weeksDiff;
    }
    
    if (in_array('total_revenue', $selectedKpis)) {
      $baseRevenue = rand(25000, 45000); // Base revenue per week in TND
      $demoData['total_revenue'] = $baseRevenue * $weeksDiff;
    }
    
    if (in_array('average_sales', $selectedKpis)) {
      $totalRevenue = $demoData['total_revenue'] ?? (rand(25000, 45000) * $weeksDiff);
      $totalOrders = $demoData['total_orders'] ?? (rand(80, 120) * $weeksDiff);
      $demoData['average_sales'] = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
    }
    
    if (in_array('total_quantities', $selectedKpis)) {
      $baseQuantities = rand(150, 300); // Base quantities per week
      $demoData['total_quantities'] = $baseQuantities * $weeksDiff;
    }
    
    if (in_array('total_clients', $selectedKpis)) {
      $baseClients = rand(30, 80); // Base clients per week
      $demoData['total_clients'] = $baseClients * $weeksDiff;
    }
    
    if (in_array('top_selling_items', $selectedKpis)) {
      $items = [
        [
          'reference' => 'WWF201',
          'name' => 'HC8 WWF Puzzle animaux des formes #',
          'quantity' => rand(25, 65),
          'revenue' => rand(200, 800)
        ],
        [
          'reference' => 'WWF084',
          'name' => 'HC8 WWF 1000 pieces puzzle - Tigres #',
          'quantity' => rand(10, 25),
          'revenue' => rand(150, 400)
        ],
        [
          'reference' => 'SP9001',
          'name' => 'Speedy Monkey - Tableau ajustable 3 en 1 #',
          'quantity' => rand(30, 60),
          'revenue' => rand(400, 900)
        ],
        [
          'reference' => 'SP7001',
          'name' => 'Speedy Monkey - Draisienne - 82x35,5x55cm #',
          'quantity' => rand(70, 120),
          'revenue' => rand(600, 1200)
        ],
        [
          'reference' => 'SP5004',
          'name' => 'Speedy Monkey - Ukulele - 41x4,5x15cm #',
          'quantity' => rand(80, 110),
          'revenue' => rand(200, 400)
        ],
        [
          'reference' => 'TECH001',
          'name' => 'iPhone 15 Pro - 256GB - Noir #',
          'quantity' => rand(15, 35),
          'revenue' => rand(3000, 4500)
        ],
        [
          'reference' => 'TECH002',
          'name' => 'Samsung Galaxy S24 - 128GB - Bleu #',
          'quantity' => rand(20, 40),
          'revenue' => rand(2500, 3500)
        ],
        [
          'reference' => 'TECH003',
          'name' => 'MacBook Air M2 - 13" - 256GB #',
          'quantity' => rand(10, 25),
          'revenue' => rand(4000, 6000)
        ],
        [
          'reference' => 'TECH004',
          'name' => 'iPad Pro 12.9" - 256GB - Gris #',
          'quantity' => rand(12, 30),
          'revenue' => rand(2000, 3500)
        ],
        [
          'reference' => 'TECH005',
          'name' => 'AirPods Pro - 2ème génération #',
          'quantity' => rand(25, 50),
          'revenue' => rand(800, 1200)
        ]
      ];
      
      // Shuffle and select random items
      shuffle($items);
      $selectedItems = array_slice($items, 0, rand(3, 5));
      
      // Convert to JSON string
      $demoData['top_selling_items'] = json_encode($selectedItems);
    }
    
    return $demoData;
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
      Log::info('Edit method called', $request->all());
      
      $validated = $request->validate([
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'fields' => ['required', 'array', 'min:1', 'in:total_orders,total_revenue,average_sales,total_quantities,total_clients,top_selling_items'],
        'users_email' => 'required|string|min:1',
        'schedule' => 'required|in:none,daily,weekly,monthly',
        'status' => 'boolean',
        'time' => 'nullable|date_format:H:i',
      ]);

      $report = Report::findOrFail($id);
      
      // Parse users_email if JSON
      $usersEmail = $validated['users_email'];
      Log::info('Original users_email', ['users_email' => $usersEmail]);
      
      if (is_string($usersEmail) && json_decode($usersEmail, true)) {
        $emails = array_column(json_decode($usersEmail, true), 'value');
        $usersEmail = implode(',', $emails);
        Log::info('Parsed emails', ['emails' => $emails, 'final_string' => $usersEmail]);
      }
      
      // Generate demo data based on selected KPIs and date range
      $demoData = $this->generateDemoDataForReport($validated['fields'], $validated['startDate'], $validated['endDate']);
      
      $data = [
        'startDate' => $validated['startDate'],
        'endDate' => $validated['endDate'],
        'users_email' => $usersEmail,
        'schedule' => $validated['schedule'],
        'time' => !empty($validated['time']) ? $validated['time'] : null,
        'status' => $request->boolean('status', true),
        'total_orders' => $demoData['total_orders'] ?? null,
        'total_revenue' => $demoData['total_revenue'] ?? null,
        'average_sales' => $demoData['average_sales'] ?? null,
        'total_quantities' => $demoData['total_quantities'] ?? null,
        'total_clients' => $demoData['total_clients'] ?? null,
        'top_selling_items' => $demoData['top_selling_items'] ?? null,
      ];

      Log::info('Final data to update', $data);
      $report->update($data);
      return redirect()->route('organisation-reports')->with('success', 'Report updated successfully with demo data.');
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('Validation failed', ['errors' => $e->errors()]);
      return back()->withErrors($e->validator)->withInput();
    } catch (Exception $ex) {
      Log::error('Error updating report: ' . $ex->getMessage());
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
