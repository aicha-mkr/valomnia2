<?php
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

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email_header' => 'required|string|max:255',
            'email_footer' => 'required|string|max:255',
        ]);

        // Create a new email template
        EmailTemplate::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'email_header' => $request->email_header,
            'email_footer' => $request->email_footer,
        ]);

        // Redirect back to the email list with a success message
        return redirect()->route('email-templates.index')->with('success', 'Template created successfully.');
    }
}