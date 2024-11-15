<?php
$conexion = mysqli_connect("localhost", "root", "1541100Luis", "inventor");

$usuario=$_POST['usuario'];
$contra=$_POST['contra'];    
session_start();


//$conexion = mysqli_connect("localhost", "root", "1541100", "sof");   
$consulta="SELECT * FROM usuario WHERE usuario='$usuario' and contra='$contra'";
$resultado=mysqli_query($conexion, $consulta); 

$filas=mysqli_num_rows($resultado);



if ($filas>0) {
    $_SESSION['usuario'] = $usuario;
     header("location: index.php");
    exit;
    
}
else{
	$_SESSION['msg'] = 'Por favor verifica la informaci&oacuten ingresada';
        header('Location: index.php');
        exit;
}

msqli_free_result($resultado);
msqli_close($conexion);
?>
    

  