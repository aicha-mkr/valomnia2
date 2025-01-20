<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmailTemplate; 
use App\Models\Recapitulatif;


class EmailTemplateController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $emailTemplates = EmailTemplate::where('user_id', $user_id)->get(); 
        return view('content.email.liste', compact('emailTemplates')); 
    }

    public function create()
    {
        $user_id = Auth::id(); 
        $emailSettings = EmailTemplate::where('user_id', $user_id)->get(); // Récupérer les modèles d'email pour l'utilisateur
        $recapData = Recapitulatif::where('user_id', $user_id)->first(); // Récupérer les données de récapitulatif pour l'utilisateur
 
        // Préparer les données de récapitulatif
        $recapDataArray = [
            'total_revenue' => $recapData->total_revenue ?? 0,
            'total_clients' => $recapData->total_clients ?? 0,
            'average_sales' => $recapData->average_sales ?? 0,
            'total_orders' => $recapData->total_orders ?? 0,
        ];
 
        return view('content.email.create', compact('emailSettings', 'recapDataArray'));
    }

    // Ajoutez d'autres méthodes comme edit(), delete(), etc.
}