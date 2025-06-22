<?php

namespace App\Http\Controllers;
use App\Models\TypeAlert;
use App\Mail\Email;
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

  public function create(Request $request)
  {
    $type = $request->query('type', 'Alert');

    if ($type === 'Alert') {
      // Fetch valid alerts from the `type_alerts` table
      $alerts = TypeAlert::select('id', 'name')->get();

      \Log::info('Alerts passed to the view:', $alerts->toArray());
      return view('content.email.create', compact('alerts', 'type'));
    }
  }

  public function store(Request $request)
  {
    $rules = [
      'type' => 'required|string|in:Alert,Rapport',
      'subject' => 'required|string|max:255',
      'title' => 'required|string|max:255',
      'content' => 'nullable|string',
      'btn_name' => 'nullable|string|max:255',
      'btn_link' => 'nullable|url',
      'alert_message' => 'nullable|string|max:255',
    ];

    // Ajustement de la validation pour `alert_id` si le type est `Alert`
    // Correction: Utiliser type_alerts au lieu de alerts
    if ($request->type === 'Alert') {
      $rules['alert_id'] = 'required|exists:type_alerts,id';
    }

    $validated = $request->validate($rules, [
      'alert_id.exists' => 'Le type d\'alerte sélectionné est invalide ou n\'existe pas.',
    ]);

    // Récupération du titre de l'alerte sélectionnée
    $alertTitle = null;
    if ($request->type === 'Alert') {
      $alert = TypeAlert::find($validated['alert_id']);
      $alertTitle = $alert ? $alert->name : null;
    }

    $templateData = [
      'user_id' => auth()->id(),
      'type' => $validated['type'],
      'subject' => $validated['subject'],
      'title' => $validated['title'],
      'content' => $validated['content'],
      'alert_id' => $request->type === 'Alert' ? $validated['alert_id'] : null,
      'alert_title' => $alertTitle,
      'btn_name' => $validated['btn_name'] ?? null,
      'btn_link' => $validated['btn_link'] ?? null,
      'has_btn' => !empty($validated['btn_name']) && !empty($validated['btn_link']),
    ];

    \Log::info('Email Template Data:', $templateData);

    // Enregistrement du template d'email
    EmailTemplate::create($templateData);

    return redirect()->route('email.liste')->with('success', 'Template créé avec succès!');
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

    // Récupérer la liste des alertes pour le formulaire si c'est un template de type Alert
    $alerts = null;
    if ($template->type === 'Alert') {
      $alerts = TypeAlert::select('id', 'name')->get();
    }

    // Identifier la vue à retourner en fonction du type
    $view = '';
    if ($template->type === 'Alert') {
      $view = 'content.email.partials.edit_alert'; // Vue pour le formulaire d'Alert
    } elseif ($template->type === 'Rapport') {
      $view = 'content.email.partials.edit_rapport'; // Vue pour le formulaire de Rapport
    } else {
      return response()->json(['error' => 'Type de template inconnu.'], 404);
    }

    // Retourner la vue sous forme de HTML avec les données nécessaires
    return view($view, compact('template', 'alerts'));
  }

  public function update(Request $request, $id)
  {
    // Trouver le template
    $template = EmailTemplate::findOrFail($id);

    // Valider la requête entrante
    $validationRules = [
      'type' => 'required|string|max:255',
      'subject' => 'required|string|max:255',
      'title' => 'required|string|max:255',
      'content' => 'nullable|string', // Autoriser un contenu vide
      'revenue_generated' => 'nullable|numeric',
      'number_of_orders' => 'nullable|integer',
      'average_basket_size' => 'nullable|integer',
      'btn_name' => 'nullable|string|max:255',
      'btn_link' => 'nullable|url',
    ];

    // Ajouter la validation pour alert_id si le type est Alert
    if ($request->type === 'Alert') {
      $validationRules['alert_id'] = 'nullable|exists:type_alerts,id';
    }

    $validated = $request->validate($validationRules);

    // Préparer les données de mise à jour
    $updateData = [
      'type' => $request->type,
      'subject' => $request->subject,
      'title' => $request->title,
      'content' => $request->content,
      'revenue_generated' => $request->revenue_generated,
      'number_of_orders' => $request->number_of_orders,
      'average_basket_size' => $request->average_basket_size,
      'btn_name' => $request->btn_name,
      'btn_link' => $request->btn_link,
      'has_btn' => !empty($request->btn_name) && !empty($request->btn_link),
    ];

    // Mettre à jour alert_id et alert_title si le type est Alert et qu'un alert_id est fourni
    if ($request->type === 'Alert' && $request->has('alert_id')) {
      $updateData['alert_id'] = $request->alert_id;

      // Récupérer le titre de l'alerte si un ID est fourni
      if ($request->alert_id) {
        $alert = TypeAlert::find($request->alert_id);
        $updateData['alert_title'] = $alert ? $alert->name : null;
      } else {
        $updateData['alert_title'] = null;
      }
    }

    // Mettre à jour le template avec les nouvelles valeurs
    $template->update($updateData);

    // Rediriger avec un message de succès
    return redirect()->route('email.liste')->with('success', 'Template mis à jour avec succès !');
  }

  public function destroy($id)
  {
    $template = EmailTemplate::findOrFail($id);
    $template->delete();

    // Rediriger vers la route de liste
    return redirect()->route('email.liste')->with('error', 'Template supprimé avec succès.');
  }

  public function sendEmail($id, $type)
  {
    \Log::info('sendEmail called with ID: ' . $id);

    // Récupérer le template d'email
    $template = EmailTemplate::findOrFail($id);

    // Préparer les données dynamiques pour les placeholders
    $data = [
      'subject' => $template->subject,
      'title' => $template->title,
      'content' => $template->content,
      'revenue_generated' => number_format($template->revenue_generated, 2, ',', ' ') . ' €',
      'number_of_orders' => $template->number_of_orders,
      'average_basket_size' => $template->average_basket_size,
      'btn_name' => $template->btn_name?? null,
      'btn_link' => $template->btn_link ?? '#',
    ];

    // Journaliser les données préparées pour le débogage
    \Log::info('Email data being sent: ', $data);

    // Envoyer l'email
    try {
      Mail::to('thabtiissam7@gmail.com')->send(new Email($data, strtolower($type)));
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
