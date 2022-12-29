<?php 
session_start();
error_reporting(0);
include "conex.php";
extract($_REQUEST);
if($_SESSION["tipo_de_usuario"] == "usuario" or $_SESSION["tipo_de_usuario"] == "super-usuario")
{
	if(!empty($_POST))
	{
		//'q' es la cantidad solicitada
		if(isset($_POST["product_id"]) && isset($_POST["q"]) && !empty($_POST["q"]))
		{
			//Si el carro esta vacio
			if(empty($_SESSION["cart"]))
			{
				switch ($_POST["type"]) 
				{
					case 'q':
					$query = pg_query($conex,"SELECT * FROM productos_q WHERE id='$_POST[product_id]'");
					$row = pg_fetch_array($query);
					break;

					case 'v':
					$query = pg_query($conex,"SELECT * FROM productos_v WHERE id='$_POST[product_id]'");
					$row = pg_fetch_array($query);
					break;

					case 'o':
					$query = pg_query($conex,"SELECT * FROM productos_o WHERE id='$_POST[product_id]'");
					$row = pg_fetch_array($query);
					break;
				}
				//Para verificar que la cantidad solicitada sea menor o igual a la existente´.
				if($_POST["q"]<=$row["cantidad"])
				{
					if($_POST["type"] == "q")
					{
					$_SESSION["cart"] = array(array("product_id"=>$_POST["product_id"],"nombre"=>$row["nombre"],"ubicacion"=>$row["ubicacion"],"estado"=>$row["estado"],"q"=>$_POST["q"],"type"=>$_POST["type"]));
					}
					else
					{
					$_SESSION["cart"] = array(array("product_id"=>$_POST["product_id"],"nombre"=>$row["nombre"],"ubicacion"=>$row["ubicacion"],"q"=>$_POST["q"],"type"=>$_POST["type"]));
					}
				}
				//Si es mayor la cantidad solicitada a la existente
				elseif($_POST["q"]>$row["cantidad"])
				{
					echo"<script>
						alert('La cantidad solicitada debe ser menor a la existente.');
						location='".$_SERVER['HTTP_REFERER']."';
						</script>";

				}
			}
			else
			{	//Para que no existan duplicados
				$repetido=false;
				foreach($_SESSION["cart"] as $c)
				{
					if($c["product_id"] == $_POST["product_id"])
					{
						$repetido=true;
						break;
					}
				}
				if($repetido)
				{
					echo "<script>alert('Error: Producto repetido.');
					location='".$_SERVER['HTTP_REFERER']."';
					</script>";
				}
				else
				{
					switch ($_POST["type"]) 
					{
						case 'q':
						$query = pg_query($conex,"SELECT * FROM productos_q WHERE id='$_POST[product_id]'");
						$row = pg_fetch_array($query);
						break;

						case 'v':
						$query = pg_query($conex,"SELECT * FROM productos_v WHERE id='$_POST[product_id]'");
						$row = pg_fetch_array($query);
						break;

						case 'o':
						$query = pg_query($conex,"SELECT * FROM productos_o WHERE id='$_POST[product_id]'");
						$row = pg_fetch_array($query);
						break;
					}
					//Para verificar que la cantidad solicitada sea menor o igual a la existente´.
					if($_POST["q"]<=$row["cantidad"])
					{
						if($_POST["type"] == "q")
						{
							array_push($_SESSION["cart"],array("product_id"=>$_POST["product_id"],"nombre"=>$row["nombre"],"ubicacion"=>$row["ubicacion"],"estado"=>$row["estado"],"q"=>$_POST["q"],"type"=>$_POST["type"]));
						}
						else
						{
							array_push($_SESSION["cart"],array("product_id"=>$_POST["product_id"],"nombre"=>$row["nombre"],"ubicacion"=>$row["ubicacion"],"q"=>$_POST["q"],"type"=>$_POST["type"]));			
						}
					}
					//Si es mayor la cantidad solicitada a la existente
					elseif($_POST["q"]>$row["cantidad"])
					{
						echo"<script>
							alert('La cantidad solicitada debe ser menor a la existente.');
							location='".$_SERVER['HTTP_REFERER']."';
							</script>";

					}
				}
			}
		}	
		echo"<script>
		location='".$_SERVER['HTTP_REFERER']."';
		</script>";
	}
}
else
{
	echo"<script>
	alert('Debes ser un usuario para realizar esta acción.');
	lcoation='index.phpe';</script>";
	session_destroy();
}
?>
