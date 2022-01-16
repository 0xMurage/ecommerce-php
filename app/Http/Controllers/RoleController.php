<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index()
    {
        $this->authorize('view', Role::class);

        return response()->json(["message" => "All roles",
            'users' => Role::with('permissions:id,name,description')->get()]);
    }


}
