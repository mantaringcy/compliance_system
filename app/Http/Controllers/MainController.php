<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        $data = User::join('roles', 'users.id', '=', 'roles.id')
        ->get();

        // dd($data);

        $data = User::join('roles', 'users.id', '=', 'roles.id')
        ->select('users.role_id', 'roles.role_name')
        ->get();

        foreach ($data as $userWithPost) {
            echo $userWithPost->role_id . ' - ' . $userWithPost->role_name;
        }

        // dd($data->role_id);

        return view('components.register', ['data' => $data]);
    }
}
