<?php 
session_start();
error_reporting(0);
if($_SESSION['tipo_de_usuario'] == 'usuario')
{
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/inicio_us.css">
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
	</div>
</header>
<body>
	<div id="hexagon1"><p>Hola <?php echo $_SESSION['nombre']?>, como <?php echo $_SESSION['tipo_de_usuario']?> tienes la capacidad, por ahora, de observar el registro de los productos existentes y hacer pedidos de éstos.</p></div>
	<div id="hexagon2"><p></p></div>
	<div id="hexagon3"><p></p></div>
	<div id="wrapper">
	<ul>
		<h1 class="qui">Productos químicos</h1>
		<li>
		<div id="qui">
			<label id="ver_q">
				<a href="productos_a_q.php"><i class="icon-file-text2"></i> Ver productos</a>

			</label><br>
		</div>
		</li>

		<h1 class="vid">Materiales de vidrio</h1>
		<li>
		<div id="vid">
			<label id="ver_v">
				<a href="productos_a_v.php"><i class="icon-file-text2"></i> Ver productos</a>
			</label><br>
		</div>
		</li>

		<h1 class="otr">Otros materiales</h1>
		<li>
		<div id="otr">
			<label id="ver_o">
				<a href="productos_a_o.php"><i class="icon-file-text2"></i> Ver productos</a>
			</label><br>
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