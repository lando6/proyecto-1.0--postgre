<?php 
require 'conex.php';
session_start();
error_reporting(0);
if($_SESSION["tipo_de_usuario"] == "admin" )
{
	switch ($_POST["type"]) 
	{
		case 'q':
		$sql = "UPDATE productos_q SET estatus='0' WHERE id='$_POST[id]' ";
		$query = pg_query($conex, $sql);
		header('location: productos_a_q.php');		
		break;
		
		case 'v':
		$sql = "UPDATE productos_v SET estatus='0' WHERE id='$_POST[id] '";
		$query = pg_query($conex,$sql);
		header('location: productos_a_v.php');
		break;

		case 'o':
		$sql = "UPDATE productos_o SET estatus='0' WHERE id='$_POST[id]' ";
		$query = pg_query($conex,$sql);
		header('location: productos_a_o.php');
		break;

		default:
			echo "<script>alert('ERROR: esta acción no puede ser realizada.')</script>";
		break;
	}
}
else
{
	echo "<script>alert('Debes iniciar sesión para realizar esta acción');
	location='index.php';</script>";
}
?>