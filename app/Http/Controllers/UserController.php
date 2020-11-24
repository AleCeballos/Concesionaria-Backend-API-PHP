<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB; // LIB bd QUERY 
use App\User; //importo el modelo de usuario

class UserController extends Controller
{
  
  
  public function options(Request $request){
    
    
    return response()->json('hola',200);
  }
  
  
  
  
  //creo metodo para el registro de usuarios
  //Request recibe info por post
  public function register(Request $request){
    
    //Recojo las variables por post del request
    
    
    
    $params = json_decode($request->getContent());
    $params_array=json_decode($request->getContent(),true); //array
    
    
    //verifico que el json no venga vacio ni el campo
    $email =  (isset($params->email)) ? $params->email: null;
    $name = ( isset($params->name)) ? $params->name: null;
    $surname = ( isset($params->surname)) ? $params->surname: null;
    $role = ( isset($params->role)) ? $params->role: null;
    $password = ( isset($params->password)) ? $params->password: null;
    
    if(!is_null($email) && !is_null($password) && !is_null($name)){
      
      
      //usuario que se va a crear en la base de datos mediante el objeto User    
      
      $user = new User();
      $user->email =$email;
      $user->name =$name;
      $user->surname =$surname;
      $user->role =$role;
      
      // cifro la contraseña
      $pwd =hash('sha256',$password);
      $user->password =$pwd;
      
      //compruebo si el usuario ya existe  
      
      $isset_user = User::where('email','=',$email)->first();
      
     //validacion--------------------------------------
     $validate =\Validator::make($params_array,[
      'name'=>'required|string',
      'surname'=>'required|string',
      'email'=>'required|string|email',
      'password'=>'required|string'
     ]);
     if($validate->fails()){
     return response()->json($validate->errors(),400);
     }
    //------------------------------------------------------------


      if(!$isset_user){
        //guardo el usuario por que no esta
        $user->save();
        $data = $user;
        
      }else{
        //no guardo el usuario
        $data = array(
          'message' => 'Usuario duplicado, no puede registrarse',
          );
        return response()->json($data,409);
      }
      
    }else{
      
      

      $data= array('mensaje' => "Complete todos los campos");

      

       return response()->json($data,409);
    }
    
    // respuesta
    return response()->json($data,200);
    
    
  }
  
  //creo metodo para el login
  //Request recibe info por get
  
  public function login(Request $request){
    $jwtAuth = new JwtAuth();
      $params = json_decode($request->getContent());
        $email = ( isset($params->email)) ? $params->email:null;
         $password = ( isset($params->password)) ? $params->password:null;
         
         $params_array=json_decode($request->getContent(),true); //array

        
      //validacion--------------------------------------
     $validate =\Validator::make($params_array,[
     
      'email'=>'required|string|email',
      'password'=>'required|string'
     ]);
     if($validate->fails()){
     return response()->json($validate->errors(),400);
     }
    //------------------------------------------------------------

    
    
         $getToken = (isset($params->gettoken)) ? $params->gettoken:null; 
    
    // cifro la password que envia el usuario
    $pwd = hash('sha256',$password);
    if(!is_null($email)&& !is_null($password)&&($getToken == null || $getToken == 'false')){
      //logueamos al usuario
      $signup = $jwtAuth->signup($email,$pwd);// si le paso true como tercer param devuelvo el objeto decoded
    }elseif($getToken != null){
      $signup = $jwtAuth->signup($email, $pwd, $getToken);
    }else{
      $signup = array(
        'status'=> 'error',
        'message' =>'Envia tus datos por post'
      );
      return response()->json($signup,409);
      }
    // devuelvo el token
   

    if(is_object($signup)){
      return response()->json($signup, 200);
    }else{

      $error = array(
       
        'message' =>'Error de sesion'
      );

      return response()->json($error, 409);
    
    }

   
    
     
      
 
   
  }
  
}
