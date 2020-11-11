<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Car;

class CarController extends Controller
{

  public function options(Request $request){
    
    
    return response()->json('hola',200);
  }
  
  //---------VEO TODOS LOS AUTOS----------// 
  public function index(){
   $cars=Car::all();
    //$cars=Car::all()->load('user');
     return response()->json($cars,200);
  }
  //-------VEO UN SOLO AUTO------------//
  public function show($id){
    $carExists = Car::find($id);
    if($carExists){
      $car=Car::find($id)->load('user');
      return response()->json($car,200);
    }else{
      return array(
        'message' => 'Coche no existe'
      );
    }
  }
  //-------GUARDO AUTOS-------------// 
  public function store (Request $request){
    $hash = $request->header('Authorization',null); //
    $jwtAuth = new JwtAuth();                         //---autenticacion
    $checkToken = $jwtAuth->checkToken($hash);          //
    //en caso de check  token  ok para mi metodo por get 
    if($checkToken){
      //obtengo datos por post
      $params = json_decode($request->getContent());
      //obtengo usuario identificado
      $user= $jwtAuth->checkToken($hash,true);
      //validacion
      //$validate =\Validator::make($params,[
        //                 'title'=>'required|min:5',
        //                 'description'=>'required',
        //                 'price'=>'required',
        //                 'status'=>'required'
        //             ]);
        // if($validate->fails()){
          //     return response()->json($validate->errors(),400);
          // }
          //guardo un coche
          $car = new Car();
          $car->user_id = $user->sub;
          $car->title = $params->title;
          $car->description = $params->description;
          $car->price = $params->price;
          $car->status = $params->status;
          $car->save();
          //devuelvo el objeto car
          $data = $car;
          // echo "AUTENTICADO metodo STORE de carController ";die();
        }else{
          //echo " NO AUTENTICADO Metodo STORE de carController ";die();}
          $data = array(
            'message'=>'Login Incorrecto',
          );
          return response()->json($data,401);
        }
        return response()->json($data,200);
      }
      //--------------ACTUALIZO EL COCHE---------------// 
      public function update($id, Request $request){
        $hash = $request->header('Authorization',null); 
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        //en caso de check  token  ok para mi metodo por get 
        if($checkToken){
          //recoger parametros por post
          $parametros = json_decode($request->getContent()); //objeto 
          $params_array=json_decode($request->getContent(),true); //array
           //actualizar registro
          $car = Car::where('id',$id)->update($params_array);
          if($car){
            $data = $parametros;
          }else{
            $data = array(
              'message'=>'Coche no existe',
            );
            return response()->json($data,400);
          }
        }else{
          //devuelvo error
          $data = array(
            'message'=>'Error al borrar login incorrecto',
          );
          return response()->json($data,401);
        }
        return response()->json($data,200);
      }
      
      //--------------ELIMINO UN COCHE--------------//
      public function destroy (  $id, Request $request){
        $hash = $request->header('Authorization',null); 
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){
          $car = Car::find($id);
          //comprobar que existe el registro
          if($car){
            //borrarlo
            $car->delete();
          }else{
            $data = array(
              'message'=>'Coche no existe',   
            );
            return response()->json($data,400);
          }  
          //devolverlo
          $data = $car;
          //return response()->json($data,200);
        }else{
          
          $data = array(
            'message'=>'Error al borrar login incorrecto',
            
          );
          return response()->json($data,401);
        }
        
        return response()->json($data,200);
      }
      
      
    }