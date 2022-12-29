<?php 
session_start();
require "../conex.php";
if($_SESSION["tipo_de_usuario"] == "super-usuario")
{
	if(isset($_POST['cedula']) && isset($_POST['nombre'])  && isset($_POST['clave']) && !empty($_POST['cedula']) && !empty($_POST['nombre']) &&!empty($_POST['correo']) && !empty($_POST['tipo_usuario']))
	{
		$sql1 = "SELECT cedula FROM usuarios WHERE cedula='$_POST[cedula]' AND estatus=1";
		$consulta = pg_query($conex,$sql1);
		$resul = pg_num_rows($consulta);//para saber la cantidad de registros que se traen de un registro en particular.
		if($resul == 1)
		{
		echo "<script>
					alert('Usuario con esa cédula ya existente.');
					location='".$_SERVER['HTTP_REFERER']."';
				</script>";
		}
		else
		{
		$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
		$sql2 = "INSERT INTO usuarios VALUES(DEFAULT,'$_POST[nombre]','$_POST[cedula]','$clave','$_POST[tipo_usuario]',1,'$_POST[apellido]','$_POST[correo]')";
			if(pg_query($conex,$sql2))
			{
				echo "<script>
							alert('Usuario creado exitosamente.');
							 location='".$_SERVER['HTTP_REFERER']."';
					</script>";
			}
			else
			{
				echo "<script>
						alert('Hubo un error, intentelo de nuevo más tarde.');
						location='".$_SERVER['HTTP_REFERER']."';
					</script>";
			}
		}
	}
	else
	{
		echo "<script>
			alert('Debe llenar todos los campos');
			location='".$_SERVER['HTTP_REFERER']."';
			</script>";
	}
}
else
{
	session_destroy();
	header("location: ../index.php");
}
?>