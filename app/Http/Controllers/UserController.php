<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB; // LIB bd QUERY 
use App\User; //importo el modelo de usuario

class UserController extends Controller
{
    //creo metodo para el registro de usuarios
    //Request recibe info por post
    public function register(Request $request){

    //Recojo las variables por post del request
    


    $params = json_decode($request->getContent());


    //var_dump($json);die();

    //$json = $request->input('json',null);
    //$params = json_decode($json); // convierto a objeto




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

 if(!$isset_user){
     //guardo el usuario por que no esta
     $user->save();
     $data = array(
        'status' => 'success',
        'code'=> 200,
        'message' => 'Usuario registrado correctamente'
         );
 }else{
  //no guardo el usuario
  $data = array(
    'status' => 'error',
    'code'=> 400,
    'message' => 'Usuario duplicado, no puede registrarse'
     );

 }

   }else{
   
    $data = array(
   'status' => 'error',
   'code'=> 400,
   'message' => 'Usuario no creado'
    );
}

// respuesta
return response()->json($data,200);


    }

   //creo metodo para el login
   //Request recibe info por get

    public function login(Request $request){

        $jwtAuth = new JwtAuth();

        //recibo el Json por post
        $json = $request->input('json',null);
        $params = json_decode($json);

        //asigno valores mediante los ternarios

        $email = (!is_null($json) && isset($params->email)) ? $params->email:null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password:null;
        
        //si viene a false el token seteo eso sino true 
        $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken:null; 

             // cifro la password que envia el usuario
             $pwd = hash('sha256',$password);

              if(!is_null($email)&& !is_null($password)&&($getToken == null || $getToken == 'false')){


                //logueamos al usuario
                $signup = $jwtAuth->signup($email,$pwd);// si le paso true como tercer param devuelvo el objeto decoded
                    
               
              }elseif($getToken != null){
                //var_dump($getToken);die();

                //{"name":"Luis","surname":"Ceballos","email":"alejandro.voynich@gmail.com","password":"luis","gettoken":"true"}
                //identifico al usuario asignandole get token
                    $signup = $jwtAuth->signup($email, $pwd, $getToken);
            
                   
                   }else{
            
                    $signup = array(
                        'status'=> 'error',
                        'message' =>'Envia tus datos por post'
                       );
                   }

               // devuelvo el token
               return response()->json($signup, 200);
           }
       
      }
