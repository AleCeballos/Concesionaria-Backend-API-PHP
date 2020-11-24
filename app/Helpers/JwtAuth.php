<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

public $key;
public function __construct(){
    $this->key = 'esta-es-mi-clave-secreta-$%7987465898789546';
}
//si llega true devuelvo el token mediante este metodo
  public function signup($email, $password, $getToken=null){
//compruebo que el usuario exista en base de datos
    $user =User::where(
         array(
             'email'=>$email,
            'password'=>$password
        ))->first();

        $signup = false;


        if(is_object($user)){
             $signup = true;
        }if($signup){
         //si es verdadero genero el token y lo devuelvo
        //array con todos los datos del usuario     
       $token= array(
          'sub'=>$user->id,
            'email'=>$user->email,
              'name'=>$user->name,
                'surname'=>$user->surname,
                  'iat'=>time(), // tiempo de sesion
                    'exp'=>time()+(7*24*60*60)
       );
       
       //n1
       $jwt = JWT::encode($token,$this->key,'HS256');
       //n2
       $decoded = JWT::decode($jwt,$this->key,array('HS256')); 
      //devuelvo el uno o el dos para autenticar al usuario SI/NO
       if(is_null($getToken)){
 // $tokenEnJson = json_encode($jwt); 
        $user->token = $jwt;
        return $user;
       }else{
           //return $decoded;
                 }}else{
// $data = array(
//             'message'=>'Error de sesion'
//           );

          //return response()->json("error de sesion",409);//ARREGLAR
          return 1;
          
      }}//recibo el token lo decodifico si es objeto devuelvo true sino false y en caso de pasarle get identity ya lo decodifico
public function checkToken($jwt,$getIdentity =false){
$auth=false;
    try{
        $decoded =JWT::decode($jwt,$this->key,array('HS256'));
    }catch(\UnexpectedValueException $e){
        $auth=false; 
    }catch(\DomainException $e){
        $auth=false; }
     
   if(isset($decoded) &&is_object($decoded) && isset($decoded->sub)){ //controlar
    $auth=true; 
   }else{
    $auth=false; 
    }
if($getIdentity){
 
    return $decoded;
}
 return $auth;  
}}