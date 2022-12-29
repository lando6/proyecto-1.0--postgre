<?php
session_start();
error_reporting(0);
if($_SESSION["tipo_de_usuario"] == "admin" or $_SESSION["tipo_de_usuario"] == "super-usuario")
{
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registrar productos</title>
	<link rel="stylesheet" type="text/css" href="css/agregar_p_v.css">
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
			<a href="productos_a_v.php">
			<p><i class="icon-file-text2"></i> Lista de productos de vidrio</p>
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
	<h1 id="title">Agregar producto de vidrio</h1>
	<form method="POST" enctype="multipart/form-data" action="accion.php">
		<table id="tb1">
			<tr>
				<th>Nombre:</th>
				<td><input type="text" name="nombre" placeholder="Nombre"></td>
			</tr>
			<tr>
				<th>Ubicación:</th>
				<td>
					<input type="text" name="ubicacion" placeholder="Estante y piso">
				</td>
			</tr>
			<tr>
				<th>Características:</th>
				<td><textarea name="caracteristicas" placeholder="Introduzca características adicionales del producto si lo desea, no más de 60 carácteres." rows="4" cols="30"></textarea></td>
			</tr>
			<tr>
				<th>Cantidad:</th>
				<td>
					<input type="number" min="0.001" step="0.001" name="cantidad">
				</td>
			</tr>
			<tr>
				<th>Imágen:</th>
				<td><input type="file" name="imagen" accept="image/png,image/jpeg"></td>
			</tr>
			<tr>
				<td><input 
					type="submit" 
					name="guardar" 
					value="Guardar"
					></td>
				<td><input type="hidden" name="i" value="3"></td>
			</tr>
		</table>
	</form>
</body>
<?php
}
else
{
	session_destroy();
	echo"<script>
	alert('Debes iniciar sesión para estar en esta página.');
	location='index.php';
	</script>";
}?>
</html>