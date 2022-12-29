<?php 
session_start();
error_reporting(0);
if($_SESSION['tipo_de_usuario'] == 'admin' or $_SESSION["tipo_de_usuario"] == "super-usuario")
{
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/inicio_ad.css">
	<link rel="stylesheet" type="text/css" href="fonts/style.css">
</head>
<header>	
	<div id="ad">
		<div id="hola">
			<h1>Bienvenido <?php echo $_SESSION['nombre']?></h1>
		</div>
		<div id="exit">
			<a href="salir.php">
			<p><i class="icon-switch"></i> Salir</p>
			</a>
		<div>
		<?php
		if($_SESSION["tipo_de_usuario"] == "super-usuario")
		{
		?>
		<div id="ag_us">
			<a href="registrar.php">
			<p><i class="icon-user-plus"></i> Agregar usuario</p>
			</a>
		</div>
		<div id="ver_us">
			<a href="usuarios.php">
			<p><i class="icon-users"></i> Ver Usuarios</p>
			</a>
		</div>
		<?php
		}
		?>
	</div>
</header>
<body>
	<div id="hexagon1"><p>Hola <?php echo $_SESSION['nombre']?>, como <?php echo $_SESSION['tipo_de_usuario']?>
	<?php 
	if($_SESSION['tipo_de_usuario'] == "admin")
	{
		echo "tienes la capacidad, por ahora, de agregar productos, modificarlos y eliminarlos de la base de datos.";
	}
	else
	{
		echo "tienes la capacidad de agregar usuarios o eliminarlos, así como puedes hacer pedidos de la lista de registro.";
	}
	?></p></div>
	<div id="hexagon2"><p></p></div>
	<div id="hexagon3"><p></p></div>
	<div id="wrapper">
	<ul>
		
		<li>
		<h1 class="qui">Productos químicos</h1>
		<div id="qui">
			<label id="ver_q">
				<a href="productos_a_q.php"><i class="icon-file-text2"></i> Ver productos</a>
			<?php 
			if($_SESSION["tipo_de_usuario"] == "admin" or $_SESSION["tipo_de_usuario"] == "super-usuario" ){
			?>
			</label><br>
			<label id="agr_q">
				<a href="agregar_p_q.php"><i class="icon-folder-plus"></i> Agregar productos</a>
			</label>
			<?php } ?>
		</div>
		</li>

		
		<li>
		<h1 class="vid">Materiales de vidrio</h1>
		<div id="vid">
			<label id="ver_v">
				<a href="productos_a_v.php"><i class="icon-file-text2"></i> Ver productos</a>
			</label><br>
			<?php if($_SESSION["tipo_de_usuario"] == "admin" or $_SESSION["tipo_de_usuario"] == "super-usuario") { ?>
			<label id="agr_v">
				<a href="agregar_p_v.php"><i class="icon-folder-plus"></i> Agregar productos</a>
			</label>
			<?php } ?>
		</div>
		</li>

		
		<li>
		<h1 class="otr">Otros materiales</h1>
		<div id="otr">
			<label id="ver_o">
				<a href="productos_a_o.php"><i class="icon-file-text2"></i> Ver productos</a>
			</label><br>
			<?php 
			if($_SESSION["tipo_de_usuario"] == "admin" or $_SESSION["tipo_de_usuario"] == "super-usuario") { ?>
			<label id="agr_o">
				<a href="agregar_p_o.php"><i class="icon-folder-plus"></i> Agregar productos</a>
			</label>
			<?php } ?>
		</div>
		</li>
	</ul>
	</div>
	<div id="footer">
		<hr>
		<p><a href="http://www.ucv.ve/">Universidad Central de Venezuela</a> | <a href="http://www.ciens.ucv.ve/ciens/">Facultad de Ciencias</a> | <a href="http://www.ciens.ucv.ve/ciens/quimica/">Escuela de Química</a><p><br>
		<p><a href="#">Powered by: OrGa</a></p>
	</div>
</body>
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
</html>