<?php 
session_start();
error_reporting(0);
if($_SESSION["tipo_de_usuario"] == "super-usuario")
{
?>
<!DOCTYPE html>
<html>
<head>
	<title>Listado de usuarios</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/usuarios.css">
	<link rel="stylesheet" type="text/css" href="fonts/style.css">	
</head>
<header>	
	<div id="ad">
		<div id="regresar">
			<a href="inicio_ad.php">
			<p><i class="icon-undo2"></i> Regresar</p>
			</a>
		</div>
		<div id="agregar"> 
			<a href="registrar.php">
			<p><i class="icon-user-plus"></i> Agregar Usuario</p>
			</a>
		</div>
		<div id="exit">
			<a href="salir.php">
			<p><i class="icon-switch"></i> Salir</p>
			</a>
		<div>
		<div id="buscar">
				<p><i class="icon-eye"></i> Buscar:</p>
				<input type="text" name="buscar" placeholder="escriba el nombre del usuario" id="caja_busqueda">
		</div>
	</div>
</header>
<body>
	<div id="div_table">
	</div>
	<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="js/main_us.js"></script>
</body>
<?php 
}
else
{
	session_destroy();
	echo "<script>
		alert('Debes iniciar sesión para poder estar en esta página.');
		location='index.php';
		</script>";
}
?>
</html>
