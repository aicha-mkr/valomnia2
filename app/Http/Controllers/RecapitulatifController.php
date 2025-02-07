<?php
namespace App\Http\Controllers;
use App\Models\EmailTemplate;
use App\Models\Recapitulatif;
use App\Services\ValomniaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecapitulatifController extends Controller
{
    protected $valomniaService;

    public function __construct(ValomniaService $valomniaService)
    {
        $this->valomniaService = $valomniaService;
    }

    public function generateRecapitulatif(Request $request, $operationId)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    
        // Validate operation ID
        if (!$this->isValidOperationId($operationId)) {
            return response()->json(['error' => 'Invalid operation ID'], 400);
        }
    
        // Calculate KPI based on the operation ID
        $recapData = $this->valomniaService->calculateKPI($operationId);
    
        // Store recap data into the database
        $this->valomniaService->storeRecapData($recapData);
    
        // Return a response with the calculated data
        return response()->json([
            'message' => 'Recapitulatif generated successfully',
            'data' => $recapData
        ], 200);
    }
    
    // Méthode d'exemple pour vérifier la validité de l'ID d'opération
    private function isValidOperationId($operationId)
    {
        // Logique pour vérifier si l'ID d'opération est valide
        // Par exemple, vérifier s'il existe dans la base de données
        return true; // Remplacez par la logique réelle
    }
}