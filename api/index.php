<?php
require_once('config.php');

$bd = mysqli_connect(DBHOST, DBUSER, DBPASS, DBBASE);

$metodo = strtolower($_SERVER['REQUEST_METHOD']);
$comandos = explode('/', strtolower($_GET['value']));
$funcionNombre = $metodo.ucfirst($comandos[0]);

$parametros = array_slice($comandos, 1);
if(count($parametros) >0 && $metodo == 'get')
	$funcionNombre = $funcionNombre.'ConParametros';


if(function_exists($funcionNombre))
	call_user_func_array ($funcionNombre, $parametros);
else
  header(' ', true, 400);

if(!$bd) {
		header(' ',true, 500);
		print mysqli_error();
		die;
  }
  

function getUsuarios(){
  echo ('entro al get');
    $link=mysqli_connect(DBHOST,DBUSER,DBPASS,DBBASE);
    if(!$link){
		header(' ',true,500); 
		print mysqli_error();
		die;
    }
    mysqli_set_charset($link, 'utf8');
    $query=mysqli_query($link,"SELECT * FROM Usuario");
    $usuarios=[];
    while($usuario=mysqli_fetch_assoc($query)){
            $usuarios[]=$usuario;
    }
    header('Content-Type: application/json');
    print json_encode($usuarios);
    mysqli_free_result($query);
    mysqli_close($link);
}

function postUsuarios(){
  $link=mysqli_connect(DBHOST,DBUSER,DBPASS,DBBASE);
    if(!$link){
    header(' ',true,500); 
    print mysqli_error();
    die;
    }
  mysqli_set_charset($link, 'utf8');
  $usuario=json_decode(file_get_contents('php://input'), true);
  $nombre=mysqli_real_escape_string($link, $usuario['nombre']);
  $mail=mysqli_real_escape_string($link, $usuario['mail']);
  $contrasenia=mysqli_real_escape_string($link, $usuario['contrasenia']);
  $q=("INSERT INTO usuario (nombre, mail, contrasenia) VALUES ('$nombre', '$mail', '$contrasenia')");
  $query=mysqli_query($link,$q);  
  if ($query){
    header(' ', true, 201);
  }else{
    header (' ', true, 500);
  }
  mysqli_free_result($query);
  mysqli_close($link);
  }
  ?>