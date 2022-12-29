<?php 
session_start();
error_reporting(0);
require 'conex.php';
if($_SESSION["tipo_de_usuario"] == "usuario" or $_SESSION["tipo_de_usuario"] == "super-usuario")
{
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pedido</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/cart.css">
	<link rel="stylesheet" type="text/css" href="fonts/style.css">
</head>
<header>
	<div id="ad">
		<div id="regresar">
			<a href="<?php echo 'inicio_ad.php'; ?>"><p><i class="icon-undo2"></i> Regresar</p></a>
		</div>
		<div id="exit">
			<a href="salir.php"><p><i class="icon-switch"></i> Salir</p></a>
		</div>
	</div>
</header>
<body>
		<div id="row">
				<h1>Pedido</h1>
				<?php
				if(isset($_SESSION["cart"]))
				{
				?>
				<p>Antes de realizar el pedido, es recomendable que guarde el PDF de los productos que está solicitando, en la sección "Ver PDF".</p>
				<table class="tabla_datos">
					<thead>
						<th>Producto</th>
						<th>Ubicación</th>
						<th>Estado</th>
						<th>Cantidad solicitada</th>
						<th><a href="#" onclick="window.open('pdf/index.php','popUpWindow','height=400,width=600,left=10,top=10,scrollbars=yes,menubar=no');">Ver PDF</a></th>
					</thead>
					<tr>
					<?php 
					foreach($_SESSION["cart"] as $c)
					{
						if($c["type"] == "q")
						{
					?>
						<td><?php echo $c["nombre"]?></td>
						<td><?php echo $c["ubicacion"]?></td>
						<td><?php echo $c["estado"]?></td>
						<td><?php echo $c["q"]?></td>
					<?php
						}
						else
						{
					?>
						<td><?php echo $c["nombre"]?></td>
						<td><?php echo $c["ubicacion"]?></td>
						<td>-</td>
						<td><?php echo $c["q"]?></td>
					<?php
						}
					?>
						<td>
							<a href="delfromcart.php?id=<?php echo $c['product_id'];?>">Eliminar</a>
						</td>
					</tr>
					<?php	
					}
					?>
				</table>
				<div id="mail">
						<button type="submit" onclick="location='php/mail.php';">Enviar solicitud</button>
				</div>
				<?php 	
				}
				else
				{
				?>
				<p id="no">No ha realizado ningún pedido.</p>
				<?php
				}
				?>
		</div>
</body>
</html>
<?php 
}
else
{
	echo "<script>alert('Debes iniciar sesión para estar en esta página.');
	location='index.php';</script>";
	session_destroy();
}
?>