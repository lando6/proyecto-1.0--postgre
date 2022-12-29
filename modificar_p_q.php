<?php 
	session_start();
	error_reporting(0);
	require "conex.php";
	if($_SESSION['tipo_de_usuario'] == 'admin')
	{
	$sql = "SELECT * FROM productos_q WHERE id='$_REQUEST[id]' AND estatus=1";
	$query = pg_query($conex, $sql);
	$fila = pg_fetch_array($query);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modificar</title>
	<link rel="stylesheet" type="text/css" href="css/modificar_q.css">
	<link rel="stylesheet" type="text/css" href="fonts/style.css">
</head>
<header>	
	<div id="ad">
		<div id="regresar">
			<a href="productos_a_q.php">
			<p><i class="icon-undo2"></i> Regresar</p>
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
	<h1 id="title">Modificar producto</h1>
	<form method="POST" enctype="multipart/form-data" action="accion.php">
	<table id="wrapper">
		<tr>
			<th>Nombre: </th>
			<td>
				<input type="text" name="nombre" placeholder="Nombre" 
				value="<?php if (@$fila['nombre'])
				{echo $fila['nombre'];} ?>">
			</td>
		</tr>
		<tr>
			<th>Estado: </th>
			<td>
				<input type="text" name="estado" placeholder="Estado" 
				value="<?php if (@$fila['estado'])
				{echo $fila['estado'];} ?>">
			</td>
		</tr>
		<tr>
			<th>Ubicación: </th>
			<td>
				<input type="text" name="ubicacion" placeholder="Ubicación" 
				value="<?php if (@$fila['ubicacion'])
				{echo $fila['ubicacion'];} ?>">
			</td>
		</tr>
		<tr>
			<th>Cantidad: </th>
			<td>
				<input type="number" name="cantidad" placeholder="Cantidad"
				value="<?php if (@$fila['cantidad']) 
				{echo $fila['cantidad'];} ?>">
			</td>
		</tr>
		<tr>
			<th>Unidad: </th>
			<td>
				<input type="text" name="unidad" placeholder="Unidad" 
				value="<?php if (@$fila['unidad'])
				{echo $fila['unidad'];} ?>">
			</td>
		</tr>
		<tr>
			<th>Ficha técnica:</th>
			<td>
				<input type="file" name="ficha_tecnica" accept="application/pdf">
			</td>
		</tr>
		<tr>
			<th>Características: </th>
			<td>
				<textarea cols="30" rows="4" name="caracteristicas"><?php if(@$fila['caracteristicas']){echo $fila['caracteristicas'];} ?></textarea>
			</td>
		</tr>
			<tr>
				<th>Numero de CAS:</th>
				<td><input type="text" name="cas" placeholder="N° de CAS" value="<?php if (@$fila['cas'])
				{echo $fila['cas'];} ?>"></td>
			</tr>
		<tr>
			<td>
				<input type="hidden" name="i" value="2">
				<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
				<input type="submit" name="submit" value="Modificar">
			</td>
		</tr>
	</table>
	</form>
<?php 
}
else
{
	session_destroy();
	echo "<script>
		alert('Debes iniciar sesión para estar en esta página.');
		location='index.php';
		</script>";
}
?>
</body>
</html>