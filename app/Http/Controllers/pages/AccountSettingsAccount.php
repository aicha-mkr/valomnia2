<?php
namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSettingsAccount extends Controller
{
public function index()

{
    if (!Auth::check()) {
        return redirect()->route('login'); // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
    }

  }
public function update(Request $request)
{
    // Valider les données de la requête
    $request->validate([
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'organization' => 'nullable|string|max:255',
        'phoneNumber' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    // Récupérer l'utilisateur authentifié
    $user = Auth::user();

    // Mettre à jour les informations de l'utilisateur
    $user->first_name = $request->firstName;
    $user->last_name = $request->lastName;
    $user->email = $request->email;
    $user->organization = $request->organization;
    $user->phone_number = $request->phoneNumber;
    $user->address = $request->address;

    // Enregistrer les modifications
    $user->save();

    // Rediriger avec un message de succès
    return redirect()->route('account.settings')->with('success', 'Profile updated successfully!');
}}