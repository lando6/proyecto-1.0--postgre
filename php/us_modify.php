<?php
session_start();
//error_reporting(0);
require "../conex.php";
extract($_REQUEST);

switch($modify)
{
	case 1:
		$query = pg_query($conex,"SELECT id FROM usuarios WHERE id='$_REQUEST[user_id]'");
		$assoc = pg_fetch_assoc($query);

		$_SESSION["user_id"] = $assoc["id"];
		header("location:".$_SERVER['HTTP_REFERER']);
		// echo "<script>
		// 			alert('Exito vale.');
		// 			 location='".$_SERVER['HTTP_REFERER']."';
		// 	</script>";
	break;

	case 2:
		$query = pg_query($conex,"UPDATE usuarios(nombre,apellido,cedula,correo,tipo_usuario) SET nombre='$_REQUEST[nombre]',apellido='$_REQUEST[apellido]',cedula='$_REQUEST[cedula]',correo='$_REQUEST[correo]',tipo_usuario='$_REQUEST[tipo_usuario]'");
		if($query)
		{
			echo "<script>alert('Exito');</script>";
			unset($_SESSION["user_id"]);
		}
	break;
}
?>