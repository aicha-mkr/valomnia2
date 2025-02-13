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
    $request->validate([
        'title' => 'required|string|max:255',
        'email_header' => 'required|string|max:255',
        'email_subject' => 'required|string|max:255',
        'content' => 'required|string', // Ensure content is validated
    ]);

    // Create a new email template
    EmailTemplate::create([
        'user_id' => auth()->id(), // Assuming the user is authenticated
        'name' => $request->title,
        'subject' => $request->email_subject,
        'content' => $request->content, // Save the content
        'template_type' => 'report',
        'is_active' => true, // Set active status
    ]);
    

    // Redirect back to the email list with a success message
 // Redirect back to the email list with a success message
 return redirect()->route('email-templates.index')->with('success', 'Template created successfully.');}
}