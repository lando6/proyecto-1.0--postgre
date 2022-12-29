<?php 
session_start();
error_reporting(0);
extract($_GET);
if($_SESSION["tipo_de_usuario"] == "usuario" or $_SESSION["tipo_de_usuario"] == "super-usuario")
{
	if(isset($_SESSION["cart"]))
	{
		if(count($_SESSION["cart"]) == 1)
		{
			unset($_SESSION["cart"]);
		}
		else
		{
			$newcart = array();
			foreach($_SESSION["cart"] as $c)
			{
				if($c["product_id"]!=$_GET["id"])
				{
					$newcart[] = $c;
				}
			}
			$_SESSION["cart"] = $newcart;
		}
	}
header("location:".$_SERVER['HTTP_REFERER']);
}
else
{
	echo"<script>
	alert('Debes iniciar sesión para poder acceder.');
	location='index.php';
	</script>";
	session_destroy();
}
?>