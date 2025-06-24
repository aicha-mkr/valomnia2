<?php

namespace App\Http\Controllers;

use App\Models\ReportHistory;
use App\Jobs\ReportStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoriqueReportController extends Controller
{
  // Afficher la liste des historiques
  public function index()
  {
    $historiqueReports = ReportHistory::with(['report', 'user'])
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    return view('content.admin.reports.history.index', compact('historiqueReports'));
  }

  // Enregistrer un nouvel historique
  public function store(Request $request)
  {
    $request->validate([
      'idreport' => 'required|integer',
      'iduser' => 'required|integer',
      'status' => 'required|boolean',
      'attempts' => 'required|integer',
    ]);

    ReportHistory::create($request->all());

    return redirect('/admin/reports/history')->with('success', 'Historique créé avec succès.');
  }

  // Afficher un historique spécifique
  public function show(ReportHistory $historiqueReport)
  {
    return view('historiqueReports.show', compact('historiqueReport'));
  }

  /**
   * Régénérer un historique de report spécifié.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function regenerate($id)
  {
    try {
      $historiqueReport = ReportHistory::findOrFail($id);

      // Mettre à jour le statut pour qu'il soit à nouveau traité
      $historiqueReport->update([
        'status' => 0, // En attente
        'attempts' => 0, // Réinitialiser le nombre de tentatives
      ]);

      // Déclencher immédiatement le report
      ReportStock::dispatch($historiqueReport->report_id);

      Log::info("Report {$historiqueReport->report_id} régénéré manuellement par l'utilisateur");

      return redirect('/admin/reports/history')->with('success', 'Report régénéré avec succès. Il sera traité prochainement.');
    } catch (\Exception $e) {
      Log::error("Erreur lors de la régénération du report : " . $e->getMessage());

      return redirect('/admin/reports/history')->with('error', 'Erreur lors de la régénération du report : ' . $e->getMessage());
    }
  }

  // Supprimer un historique
  public function destroy($id)
  {
    try {
      $historiqueReport = ReportHistory::findOrFail($id);
      $reportId = $historiqueReport->report_id;

      $historiqueReport->delete();

      Log::info("Historique du report {$reportId} supprimé");

      return redirect('/admin/reports/history')->with('success', 'Historique de report supprimé avec succès.');
    } catch (\Exception $e) {
      Log::error("Erreur lors de la suppression de l'historique du report : " . $e->getMessage());

      return redirect('/admin/reports/history')->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
    }
  }
}
