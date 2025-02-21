<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();
        return  view('content.users.index',['users'=>$users]);
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        // Récupérer un utilisateur par son ID
        $user = User::with('actions')->findOrFail($id);
        return view('user.show', compact('user'));

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }






        // Les méthodes existantes...

        /**
         * Search users by ID.
         */
        public function searchUsersById(Request $request)
        {
            $userId = $request->input('user_id');

            if (!$userId) {
                return response()->json(['message' => 'User ID is required'], 400);
            }

            $user = User::find($userId);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json($user);
        }


}