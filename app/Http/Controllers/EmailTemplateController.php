<?php
// app/Http/Controllers/EmailTemplateController.php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all(); // Fetch all templates
        return view('content.email.liste', compact('templates'));
    }

    public function create()
    {
        return view('content.email.create'); // Render the create template view
    }
    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id); // This will throw a 404 if not found
        $template->delete();
    
        return redirect()->route('email-templates.index')->with('success', 'Template deleted successfully.');
    }
    public function show($id)
{
    $template = EmailTemplate::findOrFail($id); // Fetch the template by ID
    return view('content.email.show', compact('template')); // Return the view with the template data
}

public function store(Request $request)
{
    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'total_revenue' => 'nullable|boolean',
        'total_orders' => 'nullable|boolean',
        'total_employees' => 'nullable|boolean',
        'top_selling_items' => 'nullable|string',
        'btn_name' => 'nullable|string|max:255',
        'btn_link' => 'nullable|url',
        'template_type' => 'required|string|in:report,alert', // Validation du type
    ]);

    // Créer une nouvelle entrée dans la base de données
    EmailTemplate::create([
        'user_id' => auth()->id(),
        'name' => $request->name,
        'subject' => $request->subject,
        'total_revenue' => $request->input('total_revenue', false),
        'total_orders' => $request->input('total_orders', false),
        'total_employees' => $request->input('total_employees', false),
        'top_selling_items' => $request->input('top_selling_items'),
        'btn_name' => $request->btn_name,
        'btn_link' => $request->btn_link,
        'has_btn' => !empty($request->btn_name),
        'template_type' => 'report', // Assurez-vous d'assigner un type approprié
    ]);

    // Rediriger vers la même page avec un message de succès
    return redirect()->back()->with('success', 'Template créé avec succès !');
}}