<?php
session_start();

$idexpediente=$_REQUEST['id'];
$usuario=$_SESSION['usuario'];

include '../enlace/conexion.inc';


$query="SELECT ar.idarea FROM area ar, trabajador tb,usuario us WHERE us.idtrabajador=tb.idtrabajador and tb.idarea=ar.idarea and us.idusuario='$usuario';";
$consar=mysqli_query(conectar(),$query);
$ida=mysqli_fetch_assoc($consar);
$idar=$ida['idarea'];

$query1="UPDATE expediente set idestadoexp='1' where idexpediente='$idexpediente' and idarea='$idar';";
$consulta=mysqli_query(conectar(),$query1);

if($consulta){
  header("Location: archivo.php");
  }else{
    echo "<script language='JavaScript' type='text/javascript'>
    alert('Fatal error');
    </script>";
  }
?>
