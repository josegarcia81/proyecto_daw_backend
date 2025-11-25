<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllUsers(){
        try{
            $users = User::select('id','usuario')->paginate(15);
            // $user1 = new User();
            //     $user1->id = 0;
            //     $user1->name = 'Paco';
            // $user2 = new User();
            //     $user2->id = 1;
            //     $user2->name = 'Pedro';
            
            // $users = [$user1,$user2];

            return response()->json([
                'status' => 'success',
                'code' => 200,
                "time" => now()->toIso8601String(), // Fecha en formato ISO-8601
                "message" => "Todos los usuarios obtenidos correctamente",
                'data' => $users
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Ocurrió un error con la base de datos',
                'error' => $e->getMessage()
            ], 500);   
        }
    }

    /**
     * Display the specified resource.
     */
    public function getUser($id){

        

        try{
            $usuario = User::find($id);
            if ($usuario && $usuario instanceof User && $usuario->id > 0) {
                // $user = new User();
                //     $user->id = 0;
                //     $user->name = 'Paco';
                
                return response()->json([
                                        "status" => "success",
                                        "code" => 200,
                                        "time" => now()->toIso8601String(), // Fecha en formato ISO-8601
                                        "message" => "Usuario encontrado",
                                        "data" => $usuario
                                    ], 200);
            } else {
                return response()->json([
                    "status" => "error",
                    "code" => 406,
                    "time" => now()->toIso8601String(),
                    "message" => "Usuario no Encontrado",
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
