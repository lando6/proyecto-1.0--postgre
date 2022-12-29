<?php 
session_start();
error_reporting(0);
require "conex.php";
if($_SESSION["tipo_de_usuario"] == "super-usuario")
{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Registro</title>
	<link rel="stylesheet" type="text/css" href="css/registrar.css">
	<link rel="stylesheet" type="text/css" href="fonts/style.css">
</head>
<header>
		<div id="ad">
		<div id="regresar">
			<a href="inicio_ad.php">
			<p><i class="icon-undo2"></i> Regresar</p>
			</a>
		</div>
		<div id="verq">
			<a href="usuarios.php">
			<p><i class="icon-users"></i> Lista de usuarios</p>
			</a>
		</div>
		<div id="exit">
			<a href="salir.php">
			<p><i class="icon-switch"></i> Salir</p>
			</a>
		<div>
	</div>
</header>
<body>
	<div id="registrar">
	<div id="logo"></div>
	<form method="POST" action="php/us_reg.php">
		<p>Registrar usuario</p>
		<table >
			<tr>
				<td>Cedula: </td>
				<td><input type="text" name="cedula" class="cedula" placeholder="Cédula"></td>
			</tr>
			<tr>
				<td>Nombre: </td>
				<td><input type="text" name="nombre" class="nombre" placeholder="Nombre del usuario"></td>
			</tr>
			<tr>
				<td>Apellido: </td>
				<td><input type="text" name="apellido" class="apellido" placeholder="Apellido del usuario"></td>
			</tr>
			<tr>
				<td>Clave: </td>
				<td><input type="password" name="clave" class="clave" placeholder="Contraseña"></td>
			</tr>
			<tr>
				<td>Correo: </td>
				<td><input type="text" name="correo" class="correo" placeholder="Correo del usuario"></td>
			</tr>
			<tr>
				<td>Tipo de usuario: </td>
				<td>
					<select name="tipo_usuario">
						<option name="admin" value="admin">Administrador</option>
						<option name="usuario" value="usuario">Usuario</option>
						<option name="super-usuario" value="super-usuario">Super usuario</option>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" class="submit" name="enviar" value="Registrar">
	</form>
	</div>
<?php 	
}
else
{
	session_destroy();
	header("location:index.php");
}
?>
</body>
</html>