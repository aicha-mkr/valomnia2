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
}