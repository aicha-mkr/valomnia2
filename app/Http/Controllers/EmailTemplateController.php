<?php

namespace App\Http\Controllers;

use App\Mail\Email; // Assurez-vous d'importer votre classe Mailable
use Illuminate\Support\Facades\Mail;
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
            'type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string', // Permettre un contenu vide
            'total_revenue' => 'nullable|numeric',
            'total_orders' => 'nullable|integer',
            'total_employees' => 'nullable|integer',
            'btn_name' => 'nullable|string|max:255',
            'btn_link' => 'nullable|url',
            'alert_message' => 'nullable|string|max:255', // Ajouter pour le message d'alerte
        ]);



        $templateData = [
            'user_id' => auth()->id(),
            'type' => $request->type,
            'subject' => $request->subject,
            'title' => $request->title, // Utilisez la valeur du champ
            'content' => $request->content, // Utilisez la valeur du champ
            'total_revenue' => $request->total_revenue,
            'total_orders' => $request->total_orders,
            'total_employees' => $request->total_employees,
            'btn_name' => $request->btn_name,
            'btn_link' => $request->btn_link,
            'has_btn' => !empty($request->btn_name) && !empty($request->btn_link),
        ];


        EmailTemplate::create($templateData);

        return redirect()->route('email.liste')->with('success', 'Template créé avec succès !');
    }

    public function show($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('content.email.show', compact('template'));
    }
    public function edit($id)
{
    $template = EmailTemplate::findOrFail($id);
    \Log::info('Template à éditer : ', [$template]);

    // Identifier la vue à retourner en fonction du type
    $view = '';
    if ($template->type === 'Alert') {
        $view = 'content.email.partials.edit_alert'; // Vue pour le formulaire d'Alert
    } elseif ($template->type === 'Rapport') {
        $view = 'content.email.partials.edit_rapport'; // Vue pour le formulaire de Rapport
    } else {
        return response()->json(['error' => 'Type de template inconnu.'], 404);
    }

    // Retourner la vue sous forme de HTML
    return view($view, compact('template'));
}


public function update(Request $request, $id)
{
    // Find the template
    $template = EmailTemplate::findOrFail($id);

    // Validate the incoming request
    $request->validate([
        'type' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'content' => 'nullable|string', // Allow empty content
        'total_revenue' => 'nullable|numeric',
        'total_orders' => 'nullable|integer',
        'total_employees' => 'nullable|integer',
        'btn_name' => 'nullable|string|max:255',
        'btn_link' => 'nullable|url',
    ]);

    // Update the template with the new values from the request
    $template->update([
        'type' => $request->type,
        'subject' => $request->subject,
        'title' => $request->title, // Update to the new value
        'content' => $request->content, // Update to the new value
        'total_revenue' => $request->total_revenue,
        'total_orders' => $request->total_orders,
        'total_employees' => $request->total_employees,
        'btn_name' => $request->btn_name,
        'btn_link' => $request->btn_link,
        'has_btn' => !empty($request->btn_name) && !empty($request->btn_link),
    ]);

    // Redirect with success message
    return redirect()->route('email.liste')->with('success', 'Template mis à jour avec succès !');
}

    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->delete();

        // Redirect to the list route
        return redirect()->route('email.liste')->with('error', 'Template supprimé avec succès.');
    }





    public function sendEmail($id, $type)
    {
        \Log::info('sendEmail called with ID: ' . $id);

        // Retrieve the email template
        $template = EmailTemplate::findOrFail($id);

        // Prepare dynamic data for placeholders
        $data = [
            'subject' => $template->subject, // Subject of the email
            'title' => $template->title, // Must exist here
            'content' => $template->content,
            'total_revenue' => number_format($template->total_revenue, 2, ',', ' ') . ' €',
            'total_orders' => $template->total_orders,
            'total_employees' => $template->total_employees,
            'btn_name' => $template->btn_name ?? 'Voir plus',
            'btn_link' => $template->btn_link ?? '#',
        ];

        // Log the prepared data for debugging
        \Log::info('Email data being sent: ', $data);

        // Send the email
        try {
            Mail::to('mokhtaraichaa@gmail.com')->send(new Email($data, strtolower($type)));
            \Log::info('Email sent successfully.');
            return redirect()->route('email.liste')->with('success', 'Email envoyé avec succès !');
        } catch (\Exception $e) {
            \Log::error('Error while sending email: ' . $e->getMessage());
            return redirect()->route('email.liste')->with('error', 'Échec de l\'envoi de l\'email.');
        }
    }

    private function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{' . $key . '}', htmlspecialchars($value), $content);
        }
        return $content;
    }



}