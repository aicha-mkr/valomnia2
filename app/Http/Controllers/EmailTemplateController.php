<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('content.email.liste', compact('templates'));
    }

    public function create()
    {
        return view('content.email.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'total_revenue' => 'nullable|numeric',
            'total_orders' => 'nullable|integer',
            'total_employees' => 'nullable|integer',
            'btn_name' => 'nullable|string|max:255',
            'btn_link' => 'nullable|url',
        ]);

        $topSellingItems = [
            'Product 1',
            'Product 2',
            'Product 3',
            'Product 4',
            'Product 5',
        ];

        EmailTemplate::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'subject' => $request->subject,
            'total_revenue' => $request->total_revenue,
            'total_orders' => $request->total_orders,
            'total_employees' => $request->total_employees,
            'top_selling_items' => json_encode($topSellingItems),
            'btn_name' => $request->btn_name,
            'btn_link' => $request->btn_link,
            'has_btn' => !empty($request->btn_name) && !empty($request->btn_link),
        ]);

        return redirect()->route('email.liste')->with('success', 'Template créé avec succès !');
    }

    public function show($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('content.email.show', compact('template'));
    }

    public function edit($id)
    {
        // Récupérer le template à partir de l'ID
        $template = EmailTemplate::findOrFail($id);
    
        // Vérifier le type de template
        $view = '';
        if ($template->name === 'Alert') {
            $view = 'email_templates.edit_alert'; // Vue pour Alert
        } elseif ($template->name === 'Report') {
            $view = 'email_templates.edit_report'; // Vue pour Report
        } else {
            return redirect()->back()->with('error', 'Type de template inconnu.');
        }
    
        // Retourner la vue appropriée avec les données
        return view($view, compact('template'));
    }
    
    

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'total_revenue' => 'nullable|numeric',
            'total_orders' => 'nullable|integer',
            'total_employees' => 'nullable|integer',
            'btn_name' => 'nullable|string|max:255',
            'btn_link' => 'nullable|url',
        ]);

        $template->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'total_revenue' => $request->total_revenue,
            'total_orders' => $request->total_orders,
            'total_employees' => $request->total_employees,
            'btn_name' => $request->btn_name,
            'btn_link' => $request->btn_link,
            'has_btn' => !empty($request->btn_name) && !empty($request->btn_link),
        ]);

        return redirect()->route('email.liste')->with('success', 'Template mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->delete();
    
        // Redirect to the list route
        return redirect()->route('email.liste')->with('error', 'Template supprimé avec succès.');
    }
    

    
}