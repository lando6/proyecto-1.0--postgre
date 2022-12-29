<?php
session_start();
include "../conex.php";
extract($_REQUEST);
error_reporting(0);
$id = $_REQUEST["user_id"];
if($_SESSION["tipo_de_usuario"] == "super-usuario")
{
	if(!empty($_REQUEST) && isset($_REQUEST) && !empty($id))
	{
		$q = "SELECT estatus FROM usuarios WHERE id='$id'";
		$query = pg_query($conex,$q);
		$assoc1 = pg_fetch_assoc($query);
		if($assoc1["estatus"] == 0)
		{
			$q = "UPDATE usuarios SET estatus=1 WHERE id='$id'";
			$query = pg_query($conex,$q);
		}
		elseif($assoc1["estatus"] == 1) 
		{
			$q = "UPDATE usuarios SET estatus=0 WHERE id='$id'";
			$query = pg_query($conex,$q);
		}
	header("location:../usuarios.php");
	}
	else
	{
		echo"<script>alert('ERROR');
			location='../usuarios.php';
			</script>";
	}

	header("location:../usuarios.php");
}
else
{
	session_destroy();
	header("location:../index.php");
}
?>