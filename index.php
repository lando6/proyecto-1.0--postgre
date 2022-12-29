<?php
session_start(); 
//error_reporting(0);
require 'conex.php';
	if(!empty($_SESSION['active'])){
		if($_SESSION["tipo_de_usuario"] == "admin" or $_SESSION["tipo_de_usuario"] == "super-usuario")
		{
		header ('location: inicio_ad.php');
		}
		elseif($_SESSION['tipo_de_usuario'] == "usuario") 
		{
		header ('location: inicio_us.php');
		}
	}else{
		if(!empty($_POST)){
			if(empty($_POST['cedula']) && empty($_POST['clave'])){
				echo "<script>alert('Debe ingresar cédula y contraseña para poder ingresar')</script>";
			}
			else{
				$clave = $_POST["clave"];
				$cedula = $_POST["cedula"];
				$regex_cedula = "/[\s\d]+/";
				$regex_clave = "/[A-Za-z0-9_-]{1,15}/";

				if(preg_match($regex_clave, $clave) && preg_match($regex_cedula,$cedula))
				{
					$query = pg_query($conex,"SELECT clave FROM usuarios WHERE cedula='$cedula' AND estatus='1'");
					if($query)
					{
						$assoc = pg_fetch_assoc($query);
						$password = $assoc["clave"];
						if(password_verify($clave,$password))
						{
							$query = pg_query($conex,"SELECT * FROM usuarios WHERE cedula='$cedula' AND estatus='1'");
							$rows = pg_num_rows($query);
							if($rows == 1)
							{
								$array = pg_fetch_array($query);

								$_SESSION['active'] = true;
								$_SESSION['id_user'] = $array['id'];
								$_SESSION['nombre'] = $array['nombre'];
								$_SESSION['apellido']  = $array['apellido'];
								$_SESSION['cedula'] = $array['cedula'];
								$_SESSION['correo'] = $array['correo']; 
								$_SESSION['tipo_de_usuario'] = $array['tipo_usuario'];

								if($array["tipo_usuario"] == "admin" or $array["tipo_usuario"] == "super-usuario")
								{
								header ('location: inicio_ad.php');
								}
								elseif($array["tipo_usuario"] == "usuario") 
								{
								header ('location: inicio_us.php');
								}
								else
								{
									echo"<script>alert('Error: tipo de usuario no existente. Si el error persiste, póngase en contacto con nosotros.');</script>";
									session_destroy();
								}
							}
						}
						else
						{
							echo "<script>alert('ERROR: Contraseña incorrecta.')</script>";
							session_destroy();
						}
					}
					else
					{
						echo "<script>alert('Error al conectarse al servidor');</script>";
						session_destroy();
					}
				}
				else
				{
					echo "<script>alert('Usuario no registrado')</script>";
					session_destroy();
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		function validar1(x,y)
		{
			var valor = x.value;

			document.getElementById('cedula-l').innerHTML='';
			if(y == 0)
			{
				document.getElementById('cedula-l').innerHTML='';
			}
			else
			{
				if(valor == '')
				{
					document.getElementById('cedula-l').innerHTML='Campo de llenado obligatorio';
				}
			}
			
		}
		function validar2(x,y)
		{
			var valor = x.value;

			document.getElementById('clave-l').innerHTML='';
			if(y == 0)
			{
				document.getElementById('clave-l').innerHTML='';
			}
			else
			{
				if(valor == '')
					{
						document.getElementById('clave-l').innerHTML='Campo de llenado obligatorio';
						x.style.borderColor = y;

					}
			}
		}

		function eventoNombre(x,y)
		{
			x.style.borderColor = y;
		}
	</script>
	<header>
		<div id="sireq">
			<p id="t1">REDEQUIM</p>
			<p id="t2">1.0</p><br>
			<p id="t3">Sistema de registro de la escuela de Química</p>
		</div>
		<div id="logo"></div>

	</header>
</head>
<link rel="stylesheet" type="text/css" href="css/index.css">
<body>
	<div id="login">
		<h2>Iniciar sesión</h2>
		<form id="form-log" method="POST">
			<div class="inputbox">
				<input 
				type="text" 
				name="cedula" 
				required 
				pattern="[\s\d]+"
				onblur="validar1(this,1)"
				onfocus="validar1(this,0)"
				onmouseover="eventoNombre(this,'#e6b800')" 
				onmouseout="eventoNombre(this,'#ffdb4d')" 
				>
				<label>Cédula del usuario</label>
				<div id="cedula-l"></div>

			</div>
			<div class="inputbox">
				<input 
				type="password" 
				name="clave" 
				required 
				onblur="validar2(this,1)"
				onfocus="validar2(this,0)" 
				pattern="[A-Za-z0-9_-]{1,15}"
				>
				<div id="clave-l"></div>
				<label>Clave de usuario</label>
			</div>
			<label id="pass_rem"><a href="#">¿Olvidó su contraseña?</a></label>
			<input type="submit" class="submit" value="Ingresar">
		</form>
</body>
</html>