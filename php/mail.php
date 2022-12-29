<?php 
session_start();
//error_reporting(0);
require("../conex.php");
include "function.php";
if($_SESSION["tipo_de_usuario"] == "usuario" or $_SESSION["tipo_de_usuario"] == "super-usuario")
{
	if(!empty($_SESSION["cart"]))
	{
		//Para guardar en la base de datos quien y que se pidió
		//'q' es cantidad
		foreach ($_SESSION["cart"] as $c) 
		{
			switch ($c["type"]) 
			{
				case 'q':
					$value= seleccionar_c_q($c["product_id"]);
					$resta= $value-$c["q"];
					modificar_q_2($c["product_id"],null,null,null,$resta,null,null,null);
				break;

				case 'v':
					$value= seleccionar_c_v($c["product_id"]);
					$resta= $value-$c["q"];
					modificar_v_2($c["product_id"],null,null,$resta,null);
				break;
				
				case 'o':
					$value= seleccionar_c_o($c["product_id"]);
					$resta= $value-$c["q"];
					modificar_o_2($c["product_id"],null,null,$resta,null);
				break;
			}

			if(registrar_pedido($c["product_id"],$c["nombre"],$c["q"],$c["ubicacion"],$c["type"],$_SESSION["nombre"],$_SESSION["apellido"],$_SESSION["cedula"],$_SESSION["correo"]))
			{
				//Para enviar el correo con los datos
				$to = "orlandojag29@gmail.com";
				$subject = "Solicitud de productos";
				$message ="<html><body>";
				$message.= "<h1>Pedido</h1><br>
							<p>
								<h3>Nombre: ".$_SESSION['nombre']."</h3>
								<h3>Apellido: ".$_SESSION['apellido']."</h3>
								<h3>Cédula: ".$_SESSION['cedula']."</h3>
								<h3>Correo: ".$_SESSION['correo']."</h3>
							</p>";

				$message.="<table border='1px' width='300px'>
							<tr>
								<th>Nombre</th>
								<th>Ubicacion</th>
								<th>Cantidad</th>
								<th>Estado</th>
							</tr>";

					if($c["type"] == "q")
					{
					$message.= "<tr>
									<td>".$c['nombre']."</td>
									<td>".$c['ubicacion']."</td>
									<td>".$c['q']."</td>
									<td>".$c['estado']."</td>
								</tr>";
					}
					else
					{
					$message.= "<tr>
									<td>".$c['nombre']."</td>
									<td>".$c['ubicacion']."</td>
									<td>".$c['q']."</td>
									<td>-</td>
								</tr>";	
					}
				$message.="</table>
							</body></html>";
				$headers  = 'MIME-Version: 1.0'."\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
				if(mail($to, $subject, $message, $headers))
				{
					echo "<script>alert('Su pedido fue realizado con éxito.');
					location='../inicio_ad.php';
					</script>";
					unset($_SESSION["cart"]);
				}
				else
				{
					echo "<script>alert('No se pudo realizar su pedido, si el error persiste contactenos.');
					location='".$_SERVER['HTTP_REFERER']."';
					</script>";	
				}
			}
			else
			{
				echo "<script>
							alert('ERROR: No se pudo registrar su pedido, si el error persite, comuniquese con nosotros.');
							location='".$_SERVER['HTTP_REFERER']."';
					</script>";
			}
		}	
	}
	else
	{
		echo "<script>location='".$_SERVER['HTTP_REFERER']."'</script>";
	}
}
else
{
	session_destroy();
	echo "<script>
		alert('Debe iniciar sesión para estar en esta página.');
		location='index.php';
		</script>";
}
?>