<?php
	extract($_REQUEST);
	error_reporting(0);
	require "conex.php";
	include "php/function.php";
	$nombre = $_REQUEST["nombre"];
	$ubicacion = $_REQUEST["ubicacion"];
	$cantidad = $_REQUEST["cantidad"];
	$caracteristicas = $_REQUEST["caracteristicas"];
	$cas = $_REQUEST["cas"];

	$regex_nombre = "/[a-zA-ZñÑáéíóúÁÉÍÓÚ,.\s\d]+/";
	$regex_estado = "/[a-zA-Z]+/";
	$regex_ubicacion = "/[a-zA-Z\d]+/";
	$regex_cantidad = "/[\s\d]+/";
	$regex_unidad = "/[a-zA-Z]+/";
	$regex_caracteristicas = "/[a-zA-ZñÑáéíóúÁÉÍÓÚ,.\s\d]+/";
	
	switch($i) 
	{
		//Q- Agregar quimico
		case 1:
		$estado = $_REQUEST["estado"];
		$unidad = $_REQUEST["unidad"];

		if(preg_match($regex_nombre, $nombre) &&
			preg_match($regex_estado, $estado) &&
			preg_match($regex_ubicacion, $ubicacion) &&
			preg_match($regex_cantidad, $cantidad) && 
			preg_match($regex_unidad, $unidad) && preg_match($regex_caracteristicas, $caracteristicas)
			)
		{
			registrar_q_1($nombre,$estado,$ubicacion,$cantidad,$unidad,$_FILES["ficha_tecnica"],$caracteristicas,$cas);
		}		
		break;

		//Q- Modificar quimico
		case 2:
		$estado = $_REQUEST["estado"];
		$unidad = $_REQUEST["unidad"];

		if(preg_match($regex_nombre, $nombre) &&
			preg_match($regex_estado, $estado) &&
			preg_match($regex_ubicacion, $ubicacion) &&
			preg_match($regex_cantidad, $cantidad) && 
			preg_match($regex_unidad, $unidad) && preg_match($regex_caracteristicas, $caracteristicas)
			)
		{
			if(!empty($nombre) && !empty($ubicacion))
			{
				modificar_q_1($id,$nombre,$estado,$ubicacion,$cantidad,$unidad,$_FILES["ficha_tecnica"],$caracteristicas,$cas);
			}
		}
		else
		{
			echo "<script>
					alert('Los datos que ingresó poseen caracteres inválidos');
					location='".$_SERVER['HTTP_REFERER']."';
				</script>";
		}
		break;



		case 3://V- Agregar producto de vidrio
		if(preg_match($regex_nombre, $nombre) &&
			preg_match($regex_ubicacion, $ubicacion) &&
			preg_match($regex_cantidad, $cantidad) && preg_match($regex_caracteristicas, $caracteristicas)
			)
		{
			if(!empty($nombre) && !empty($ubicacion) && !empty($cantidad))
			{
				registrar_v_1($nombre,$ubicacion,$cantidad,$unidad,$_FILES["imagen"],$caracteristicas);
			}
		}		
		else
		{
			echo "<script>
					alert('Los datos que ingresó poseen caracteres inválidos');
					location='".$_SERVER['HTTP_REFERER']."';
				</script>";
		}

	
		break;
		
		case 4://V- Modificar producto de vidrio
		if(preg_match($regex_nombre, $nombre) &&
			preg_match($regex_ubicacion, $ubicacion) &&
			preg_match($regex_cantidad, $cantidad) && preg_match($regex_caracteristicas, $caracteristicas)
			)
		{

			modificar_v_1($id,$nombre,$ubicacion,$cantidad,$_FILES["imagen"],$caracteristicas);
		}
		else
		{
			echo "<script>
					alert('Los datos que ingresó poseen caracteres inválidos');
					location='".$_SERVER['HTTP_REFERER']."';
				</script>";
		}
		break;

		case 5://O- Agregar otro
		if(preg_match($regex_nombre, $nombre) &&
			preg_match($regex_ubicacion, $ubicacion) &&
			preg_match($regex_cantidad, $cantidad) && preg_match($regex_caracteristicas, $caracteristicas)
			)
		{
			registrar_o_1($nombre,$ubicacion,$cantidad,$_FILES["imagen"],$caracteristicas);
		}
		else
		{
			echo "<script>
					alert('Los datos que ingresó poseen caracteres inválidos');
					location='".$_SERVER['HTTP_REFERER']."';
				</script>";
		}
		break;

		case 6://O- Modificar otro
		if(preg_match($regex_nombre, $nombre) &&
			preg_match($regex_ubicacion, $ubicacion) &&
			preg_match($regex_cantidad, $cantidad) && preg_match($regex_caracteristicas, $caracteristicas)
			)
		{
			modificar_v_1($id,$nombre,$ubicacion,$cantidad,$_FILES["imagen"],$caracteristicas);
		
		}
		else
		{
			echo "<script>
					alert('Los datos que ingresó poseen caracteres inválidos');
					location='".$_SERVER['HTTP_REFERER']."';
				</script>";
		}
		break;

		default:
		echo "<script>
				alert('ERROR INTERNO 1');
				location='".$_SERVER['HTTP_REFERER']."';
			 </script>";
		break;
	}
?>