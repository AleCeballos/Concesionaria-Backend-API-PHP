<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Car;

class CarController extends Controller
{
    //creo metodo index para los autos
   //Request recibe info por get
   public function index(Request $request){

    //recolecto la autorizacion de la cabecera de la request o si
    //no viene nada la seteo a null  ACA HAGO LA AUTENTICACION
     $hash = $request->header('Authorization',null);

     $jwtAuth = new JwtAuth();
     $checkToken = $jwtAuth->checkToken($hash);


     //en caso de check token para mi metodo por get 
if($checkToken){
    echo " AUTENTICADO  metodo  Index de carController ";die();
}else{

    echo "NO AUTENTICADO metodo Index de carController ";die();
}
   
        }



        //metodo para guardar 

        public function store (Request $request){



            $hash = $request->header('Authorization',null); 
            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);
       
       
            //en caso de check  token  ok para mi metodo por get 
             if($checkToken){
                
                 //obtengo datos por post
                $params = json_decode($request->getContent());
                
                    // $json =$request->input('json',null);
                    // $params= json_decode($json);
                    // $params_array = json_decode($json,true);
              //obtengo usuario identificado
            $user= $jwtAuth->checkToken($hash,true);
             
            
            //validacion
        //     $request->merge($params_array);
        //     try{
        //     $validate = $this->validate($request,[
        //         'title'=>'required|min:5',
        //         'description'=>'required',
        //         'price'=>'required',
        //         'status'=>'required'
        //     ]);

        // }catch(\Illuminate\Validation\ValidationException $e){
        //  return $e->getResponse();
        // }
            
            //guardo un coche
            $car = new Car();
            $car->user_id = $user->sub;
            $car->title = $params->title;
            $car->description = $params->description;
            $car->price = $params->price;
            $car->status = $params->status;

            $car->save();

            $data = array(
                  'car'=>$car,
                  'status'=>'success',
                  'code'=>200,

            ); 


          // echo "AUTENTICADO metodo STORE de carController ";die();

        
       }else{
           
        //echo " NO AUTENTICADO Metodo STORE de carController ";die();}
      
        $data = array(
            'message'=>'Login Incorrecto',
            'status'=>'error',
            'code'=>300,
        );

               }

        return response()->json($data,200);


       //PREGUNTAR SI LO DEVUELVO ASI
       //return response('success',200);


}
}