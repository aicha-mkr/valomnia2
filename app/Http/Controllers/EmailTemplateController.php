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
    $emailTemplates = EmailTemplate::where('user_id', $user_id)->get(); // Get email templates for the user
    $recapData = Recapitulatif::where('user_id', $user_id)->first(); // Get recap data for the user

    // Prepare recap data
    $recapDataArray = [
        'total_revenue' => $recapData->total_revenue ?? 0,
        'total_clients' => $recapData->total_clients ?? 0,
        'average_sales' => $recapData->average_sales ?? 0,
        'total_orders' => $recapData->total_orders ?? 0,
    ];

    return view('content.email.create', compact('emailTemplates', 'recapDataArray'));
}
    // Ajoutez d'autres méthodes comme edit(), delete(), etc.
}
