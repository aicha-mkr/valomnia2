<?php

namespace App\Http\Controllers;

use App\Models\AlertHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HistoriqueAlertController extends Controller
{
    // Afficher la liste des historiques
    public function index()
    {
        $historiqueAlerts = AlertHistory::all();
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
        return redirect()->route('historiqueAlerts.index')->with('success', 'Historique créé avec succès.');
    }

    // Afficher un historique spécifique
    public function show(HistoriqueAlert $historiqueAlert)
    {
        return view('historiqueAlerts.show', compact('historiqueAlert'));
    }

    /**
     * Regenerate a specified Historique Alert.
     *
     * @param int $id
     * @return Response
     */
    public function regenerate($id)
    {
        $historiqueAlert = AlertHistory::findOrFail($id);
        $historiqueAlert->update([
            'status' => 1, // Active status
            'attempts' => 0, // Reset number of attempts
        ]);

        return redirect()->route('historiqueAlerts.index')->with('success', 'Historique Alert regenerated successfully.');
    }


    // Supprimer un historique
    public function destroy($id)
    {
        $historiqueAlert = AlertHistory::findOrFail($id);
        $historiqueAlert->delete();
        return response()->json(null, 204); // No Content
    }

}