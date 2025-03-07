<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    //
    public function testMail (){

        Mail::to('mokhtaraichaa@gmail.com')->send(new Email());
        return response()->json(['message' => 'Email envoyé avec succès !']);
    }
}
