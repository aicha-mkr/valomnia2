<?php

namespace App\Http\Controllers;

use App\Models\AlertHistory;
use App\Jobs\AlertStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoriqueAlertController extends Controller
{
  // Afficher la liste des historiques
  public function index()
  {
    $historiqueAlerts = AlertHistory::with(['alert.type', 'user'])
      ->orderBy('created_at', 'desc')
      ->paginate(15);
    return view('content.admin.alerts.history.index', compact('historiqueAlerts'));
  }

  // Enregistrer un nouvel historique
  public function store(Request $request)
  {
    $request->validate([
      'idalert' => 'required|integer',
      'iduser' => 'required|integer',
      'status' => 'required|boolean',
      'attempts' => 'required|integer',
    ]);

    AlertHistory::create($request->all());
    return redirect('/admin/alerts/history')->with('success', 'Historique créé avec succès.');
  }

  // Afficher un historique spécifique
  public function show(AlertHistory $historiqueAlert)
  {
    return view('historiqueAlerts.show', compact('historiqueAlert'));
  }

  /**
   * Regenerate a specified Historique Alert.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function regenerate($id)
  {
    try {
      $historiqueAlert = AlertHistory::findOrFail($id);

      // Mettre à jour le statut pour qu'il soit à nouveau traité
      $historiqueAlert->update([
        'status' => 0, // En attente
        'attempts' => 0, // Réinitialiser le nombre de tentatives
      ]);

      // Déclencher immédiatement l'alerte
      AlertStock::dispatch($historiqueAlert->alert_id);

      Log::info("Alerte {$historiqueAlert->alert_id} régénérée manuellement par l'utilisateur");

      // Utiliser une URL directe au lieu d'une route nommée
      return redirect('/admin/alerts/history')->with('success', 'Alerte régénérée avec succès. Elle sera traitée prochainement.');
    } catch (\Exception $e) {
      Log::error("Erreur lors de la régénération de l'alerte: " . $e->getMessage());
      // Utiliser une URL directe au lieu d'une route nommée
      return redirect('/admin/alerts/history')->with('error', 'Erreur lors de la régénération de l\'alerte: ' . $e->getMessage());
    }
  }

  // Supprimer un historique
  public function destroy($id)
  {
    try {
      $historiqueAlert = AlertHistory::findOrFail($id);
      $alertId = $historiqueAlert->alert_id;
      $historiqueAlert->delete();

      Log::info("Historique de l'alerte {$alertId} supprimé");

      return redirect('/admin/alerts/history')->with('success', 'Historique d\'alerte supprimé avec succès.');
    } catch (\Exception $e) {
      Log::error("Erreur lors de la suppression de l'historique d'alerte: " . $e->getMessage());
      return redirect('/admin/alerts/history')->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    }
  }
}
