<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function getUser($id){

        $idVal = $id->validate([
            'id' => 'required|integer|min:0'
        ]);

        try{
            if ($id == 0) {
                $user = new User();
                $user->name = 'Guest';
                $user->email = 'test@test.com';

                return response()->json([
                                        "status" => "success",
                                        "code" => 200,
                                        "time" => now()->toIso8601String(), // Fecha en formato ISO-8601
                                        "message" => "Incidencia creada correctamente",
                                        "data" => $incidencia
                                    ], 200);
            } else {
                return response()->json([
                    "status" => "error",
                    "code" => 406,
                    "time" => now()->toIso8601String(),
                    "message" => "Incidencia no encontrada",
                    "data" => null
                ], 406);
            }
            //$user = User::findOrFail($id);
            
        }catch(\Exception $e){
            return response()->json([
                "status" => "error",
                "code" => 500,
                "message" => "Ocurrió un error con la base de datos",
                "error" => $e->getMessage()
            ], 500);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
