<?php 
include "../conex.php";
error_reporting(0);
function seleccionar_c_q($id)
{
	global $conex;
	$query= pg_query($conex,"SELECT cantidad FROM productos_q WHERE id='$id'");
	if($query)
	{
		$assoc = pg_fetch_assoc($query);
		
		return $assoc["cantidad"];
	}
}
function seleccionar_c_v($id)
{
	global $conex;
	$query= pg_query($conex,"SELECT cantidad FROM productos_v WHERE id='$id'");
	if($query)
	{
		$assoc = pg_fetch_assoc($query);
		
		return $assoc["cantidad"];
	}
}
function seleccionar_c_o($id)
{
	global $conex;
	$query= pg_query($conex,"SELECT cantidad FROM productos_o WHERE id='$id'");
	if($query)
	{
		$assoc = pg_fetch_assoc($query);
		
		return $assoc["cantidad"];
	}
}
//Para registrar químicos con ficha técnica
function registrar_q_1($nombre,$estado,$ubicacion,$cantidad,$unidad,array $ficha,$caracteristicas,$cas)
{
	global $conex;	
	if(!empty($nombre) && !empty($ubicacion))
	{
		$query =pg_query($conex,"SELECT nombre,ubicacion FROM productos_q WHERE nombre='$nombre' AND ubicacion='$ubicacion'");
		if($query)
		{
			if(array_sum($ficha) > 4)
			{
				if($row = pg_num_rows($query)>0)
				{
					echo "<script>alert('Producto ya existente.');
							location='".$_SERVER['HTTP_REFERER']."';
							</script>";
				}
				else
				{
						//Verifica que el documento sea de tipo PDF
						$finfo = finfo_open(FILEINFO_MIME_TYPE);
						$mime = finfo_file($finfo,$ficha["tmp_name"]);
						if($mime == "application/pdf")
						{
							move_uploaded_file($ficha['tmp_name'],'fichas/'.$ficha['name']);
							$ruta = 'fichas/'.$ficha['name'];
							$sql = "INSERT INTO productos_q VALUES (DEFAULT,'$nombre','$estado','$ubicacion','$cantidad','$unidad','$ruta','$caracteristicas',1,'$cantidad','$cas');";
							if(pg_query($conex,$sql))
							{
								echo "<script type='text/javascript'>
											if(confirm('Producto guardado, ¿desea seguir agregando productos?'))
												{
													location='agregar_p_q.php';
												}
												else 
												{
													location='inicio_ad.php';
												}
										 </script>";
							}
							else
							{
								echo "<script>
									alert('No se pudo guardar el producto, verifique bien los datos.');
									location='".$_SERVER['HTTP_REFERER']."';
									</script>";
							}
						}
						else
						{
							echo"<script>alert('El archivo debe ser formato PDF.');
									location=(".$_SERVER['HTTP_REFERER'].");</script>";
						}
				}
			}
			elseif(array_sum($ficha) == 4)
			{
				registrar_q_2($nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas,$cas);
			}
		}
		else
		{
			echo "<scrip>alert('Error de conexión. Intentelo de nuevo más tarde.');
			location='".$_SERVER['HTTP_REFERER']."';</script>";
		}
	}
}
//Para registrar químicos sin ficha técnica
function registrar_q_2($nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas,$cas)
{
	global $conex;
	if(pg_query($conex,"INSERT INTO productos_q (id,nombre, estado, ubicacion, cantidad, unidad, caracteristicas, estatus, patron, cas) VALUES (DEFAULT,'$nombre','$estado','$ubicacion','$cantidad','$unidad','$caracteristicas',1,'$cantidad','$cas')"))
	{
			echo "<script type='text/javascript'>
					if(confirm('El producto fue registrado sin ficha técnica, es aconsejable que se le sea adjuntada una en el futuro, ¿desea seguir agregando productos?'))
					{
						location='agregar_p_q.php';
					}
					else 
					{
						location='inicio_ad.php';
					}
					</script>";
	}
	else
	{
			echo "<script>
					alert('No se pudo guardar el producto, verifique bien los datos insertados.');
					location='agregar_p_q.php';
					</script>";
	}
	
}
//Modificar químico con ficha técnica
function modificar_q_1($id,$nombre,$estado,$ubicacion,$cantidad,$unidad,array $ficha,$caracteristicas,$cas)
{
			global $conex;
			//1- Con ficha tecnica	
			if(array_sum($_FILES["ficha_tecnica"]) > 4)
			{
				//Verifica que el documento sea de tipo pdf
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $_FILES["ficha_tecnica"]["tmp_name"]);
				if($mime == "application/pdf")
				{
					//1- Se verifica con el patron
					$sql_1 = pg_query($conex,"SELECT patron FROM productos_q WHERE id='$id'");
					$assoc = pg_fetch_assoc($sql_1);
					//Si el valor de entrada es menor, se mantiene el patron
					if($assoc["patron"]>=$cantidad)
					{
						$ruta = 'fichas/'.$_FILES['ficha_tecnica']['name'];
						move_uploaded_file($_FILES['ficha_tecnica']['tmp_name'],$ruta);
						$sql = "UPDATE productos_q SET nombre='$nombre', estado='$estado', ubicacion='$ubicacion', cantidad='$cantidad', unidad='$unidad', ficha='$ruta', caracteristicas='$caracteristicas', cas='$cas' WHERE id='$id';";
						if(pg_query($conex, $sql))
						{
							echo "<script type='text/javascript'>
										alert('Producto modificado exitosamente.');
										location='productos_a_q.php';
								</script>";
						}
						else
						{
							echo "<script>
									alert('No se pudo realizar conexión.');
									location='inicio_ad.php';
								</script>";
						}
					}
					//2- Si la cantidad de entrada es mayor a la del patron, se modifica el patron
					elseif($assoc["patron"]<$cantidad)
					{
						$ruta = 'fichas/'.$_FILES['ficha_tecnica']['name'];
						move_uploaded_file($_FILES['ficha_tecnica']['tmp_name'],$ruta);
						$sql = "UPDATE productos_q SET nombre='$nombre', estado='$estado', ubicacion='$ubicacion', cantidad='$cantidad', unidad='$unidad', ficha='$ruta', caracteristicas='$caracteristicas', patron='$cantidad', cas='$cas' WHERE id='$id';";
						if(pg_query($conex, $sql))
						{
							echo "<script type='text/javascript'>
										alert('Producto modificado exitosamente.');
										location='productos_a_q.php';
								</script>";
						}
						else
						{
							echo "<script>
									alert('No se pudo realizar conexión.');
									location='inicio_ad.php';
								</script>";
						}
					}
					//3- Para advertir de error interno
					else
					{
						echo "<script>alert('ERROR QM13: Error interno en el sistema. Si el error persiste póngase en contacto con nosotros');
						location='".$_SERVER['HTTP_REFERER']."';
						</script>";
					}

				}
				else
				{
					 echo"<script>
				 			alert('El documento adjuntado debe ser de tipo PDF');
				 			location='".$_SERVER['HTTP_REFERER']."';
				 		</script>";
				}
			}
			//Si no posee PDF
			elseif(array_sum($_FILES["ficha_tecnica"]) == 4)
			{
			 modificar_q_2($id,$nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas,$cas);
			}
}
//Modificar químico sin ficha técnica
function modificar_q_2($id,$nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas,$cas)
{
	global $conex;
	//Se verifica con el patron
	if($nombre == null && $estado == null && $ubicacion == null)
	{
		pg_query($conex,"UPDATE productos_q SET cantidad='$cantidad' WHERE id='$id'");
	}
	else
	{
		$sql_1 = pg_query($conex,"SELECT patron FROM productos_q WHERE id='$id'");
		$assoc = pg_fetch_assoc($sql_1);
		//1- Si la cantidad de entrada es menor al patron, el patron se mantiene
		if($assoc["patron"]>=$cantidad)
		{
			if(pg_query($conex, "UPDATE productos_q SET nombre='$nombre', estado='$estado', ubicacion='$ubicacion', cantidad='$cantidad', unidad='$unidad', caracteristicas='$caracteristicas', cas='$cas' WHERE id='$id'"))
				{
					echo "<script type='text/javascript'>
								alert('El producto fue modificado.');
								location='productos_a_q.php';
							</script>";
				}
				else
				{
					echo "<script>
								alert('No se pudo realizar conexión.');
								location='inicio_ad.php';
						</script>";
				}
			}
			//2- Si la cantidad de entrada es mayor al patron, el patron se modifica
			elseif($assoc["patron"]<$cantidad)
			{
				if(pg_query($conex, "UPDATE productos_q SET nombre='$nombre', estado='$estado', ubicacion='$ubicacion', cantidad='$cantidad', unidad='$unidad', caracteristicas='$caracteristicas', patron='$cantidad', cas='$cas' WHERE id='$id'"))
				{
					echo "<script type='text/javascript'>
							alert('El producto fue modificado.');
							location='productos_a_q.php';
						</script>";
				}
				else
				{
					echo "<script>
							alert('No se pudo realizar conexión.');
							location='inicio_ad.php';
						</script>";
				}
			}
			//3- Para advertir de error interno
			else
			{
				echo"<script>alert('ERROR QM23: Error interno en el sistema. Si el error persiste póngase en contacto con nosotros');
					location='".$_SERVER['HTTP_REFERER']."';</script>";
			}		
	}
}
//Agregar producto de vidrio con imagen
function registrar_v_1($nombre,$ubicacion,$cantidad,array $imagen,$caracteristicas)
{
	global $conex;
	$rows = pg_num_rows(pg_query($conex,"SELECT nombre, ubicacion FROM productos_v WHERE nombre='$nombre' AND ubicacion='$ubicacion' AND estatus='1' "));
	if($rows > 0)
	{
		echo "<script>
				alert('Producto con ese nombre y ubicación ya registrado');
				location='agregar_p_v.php';
			</script>";
	}
	else
	{
	//Si se le adjunta una imagen al producto
		if(array_sum($_FILES["imagen"])>4)
		{
		//Se verifica que se una imagen
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo,$_FILES["imagen"]["tmp_name"]);
			if($mime == "image/jpeg" or $mime == "image/png")
			{
				move_uploaded_file($_FILES["imagen"]['tmp_name'],'imagenes/'.$_FILES['imagen']['name']);
				$ruta = 'imagenes/'.$_FILES['imagen']['name'];
				$sql = "INSERT INTO productos_v VALUES (DEFAULT,'$nombre','$ubicacion','$cantidad','$caracteristicas',1,'$cantidad','$ruta');";
				if(pg_query($conex,$sql))
				{
					echo "<script type='text/javascript'>
							if(confirm('Producto guardado, ¿desea seguir agregando productos?'))
							{
								location='agregar_p_v.php';
							}
							else 
							{
								location='inicio_ad.php';
							}
							</script>";
				}
				else
				{
					echo "<script>
							alert('No se pudo guardar el producto, verifique sí el documento adjuntado es de tipo jpg o png.');
							location='".$_SERVER['HTTP_REFERER']."';
						</script>";
				}
			}
			else
			{
				echo"<script>alert('El archivo debe ser formato JPG o PNG.');
						location=(".$_SERVER['HTTP_REFERER'].");
					</script>";
			}
		}
		//Si no se le adjunta imagen
		elseif(array_sum($_FILES["imagen"]) == 4)
		{
			registrar_v_2($nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas);			
		}
	}
}
//Registrar vidrio sin imagen
function registrar_v_2($nombre,$ubicacion,$cantidad,$caracteristicas)
{
	global $conex;
		if(pg_query($conex,"INSERT INTO productos_v (id,nombre, ubicacion, cantidad, caracteristicas, estatus, patron) VALUES (DEFAULT,'$nombre','$ubicacion','$cantidad','$caracteristicas',1,'$cantidad')"))
		{
			echo "<script type='text/javascript'>
					if(confirm('El producto fue registrado sin un foto, es aconsejable que se le sea adjuntada una en el futuro, ¿desea seguir agregando productos?'))
					{
						location='agregar_p_q.php';
					}
					else 
					{
						location='inicio_ad.php';
					}
					</script>";
		}
		else
		{
			echo "<script>
					alert('No se pudo guardar el producto, verifique bien los datos insertados.');
					location='agregar_p_q.php';
					</script>";
		}
}
//Modificar producto de vidrio con imagen
function modificar_v_1($id,$nombre,$ubicacion,$cantidad,array $imagen,$caracteristicas)
{
	global $conex;
			//1- Con imagen
			if(array_sum($imagen) > 4)
			{
				//Verifica que el documento sea de tipo jpeg o png
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $imagen["tmp_name"]);
				if($mime == "image/jpeg" or $mime == "image/png")
				{
					//1- Se verifica con el patron
					$sql_1 = pg_query($conex,"SELECT patron FROM productos_v WHERE id='$id'");
					$assoc = pg_fetch_assoc($sql_1);
					//Si el valor de entrada es menor, se mantiene el patron
					if($assoc["patron"]>=$cantidad)
					{
						$ruta = 'imagenes/'.$imagen['name'];
						move_uploaded_file($imagen['tmp_name'],$ruta);
						if(pg_query($conex, "UPDATE productos_v SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas', imagen='$ruta' WHERE id='$id';"))
						{
							echo "<script type='text/javascript'>
										alert('Producto modificado exitosamente.(11)');
										location='productos_a_v.php';
								</script>";
						}
						else
						{
							echo "<script>
									alert('No se pudo realizar conexión.(11)');
									location='inicio_ad.php';
								</script>";
						}
					}
					//2- Si la cantidad de entrada es mayor a la del patron, se modifica el patron
					elseif($assoc["patron"]<$cantidad)
					{
						$ruta = 'fichas/'.$imagen['name'];
						move_uploaded_file($imagen['tmp_name'],$ruta);
						if(pg_query($conex, "UPDATE productos_v SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas', patron='$cantidad', imagen='$ruta' WHERE id='$id';"))
						{
							echo "<script type='text/javascript'>
										alert('Producto modificado exitosamente.(12)');
										location='productos_a_v.php';
								</script>";
						}
						else
						{
							echo "<script>
									alert('No se pudo realizar conexión.(12)');
									location='inicio_ad.php';
								</script>";
						}
					}
					//3- Para advertir de error interno
					else
					{
						echo "<script>alert('ERROR VM13: Error interno en el sistema. Si el error persiste póngase en contacto con nosotros');
						location='".$_SERVER['HTTP_REFERER']."';
						</script>";
					}

				}
				else
				{
					 echo"<script>
				 			alert('El documento adjuntado debe ser de tipo PNG O JPG');
				 			location='".$_SERVER['HTTP_REFERER']."';
				 		</script>";
				}
			}
			//2- Sin imagen
			elseif(array_sum($_FILES["imagen"]) == 4)
			{
				modificar_v_2($id,$nombre,$ubicacion,$cantidad,$caracteristicas);
			}
}
//Modificar producto de vidrio sin imagen
function modificar_v_2($id,$nombre,$ubicacion,$cantidad,$caracteristicas)
{
	global $conex;
	if($nombre == null || $ubicacion == null || $caracteristicas == null)
	{
		pg_query($conex,"UPDATE productos_v SET cantidad='$cantidad' WHERE id='$id'");
	}
	else
	{
		//2-Modificar producto sin imágen
		//Se verifica con el patron
		$assoc = pg_fetch_assoc(pg_query($conex,"SELECT patron FROM productos_v WHERE id='$id'"));
		//1- Si la cantidad de entrada es menor al patron, el patron se mantiene
		if($assoc["patron"]>=$cantidad)
		{
			if(pg_query($conex, "UPDATE productos_v SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas' WHERE id='$id'"))
			{
							echo "<script type='text/javascript'>
										alert('El producto fue modificado.(21)');
										location='productos_a_v.php';
								</script>";
			}
			else
			{
							echo "<script>
									alert('No se pudo realizar conexión.(21)');
									location='inicio_ad.php';
								</script>";
			}
		}
		//2- Si la cantidad de entrada es mayor al patron, el patron se modifica
		elseif($assoc["patron"]<$cantidad)
		{
			if(pg_query($conex, "UPDATE productos_v SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas', patron='$cantidad' WHERE id='$id'"))
			{
							echo "<script type='text/javascript'>
										alert('El producto fue modificado.(22)');
										location='productos_a_v.php';
								</script>";
			}
			else
			{
							echo "<script>
									alert('No se pudo realizar conexión.(22)');

								</script>";
			}
		}
		//3- Para advertir de error interno
		else
		{
						echo"<script>alert('ERROR VM23: Error interno en el sistema. Si el error persiste póngase en contacto con nosotros');
						location='".$_SERVER['HTTP_REFERER']."';</script>";
		}
	}
}
//Registrar miscelaneo con imagen
function registrar_o_1($nombre,$ubicacion,$cantidad,array $imagen,$caracteristicas)
{
	global $conex;
	if(!empty($nombre) && !empty($ubicacion) && !empty($cantidad))
	{
				$rows = pg_num_rows(pg_query($conex,"SELECT nombre, ubicacion FROM productos_o WHERE nombre='$nombre' AND ubicacion='$ubicacion' AND estatus='1' "));
				if($rows > 0)
				{
					echo "<script>
						alert('Producto con ese nombre y ubicación ya registrado');
						location='agregar_p_o.php';
						</script>";
				}
				else
				{
					//Si se le adjunta una imagen 
					if(array_sum($imagen)>4)
					{
						//Se verifica que se una imagen
						$finfo = finfo_open(FILEINFO_MIME_TYPE);
						$mime = finfo_file($finfo,$imagen["tmp_name"]);
						if($mime == "image/jpeg" or $mime == "image/png")
						{
							move_uploaded_file($imagen['tmp_name'],'imagenes/'.$imagen['name']);
							$ruta = 'imagenes/'.$imagen['name'];
							$sql = "INSERT INTO productos_o VALUES (DEFAULT,'$nombre','$ubicacion','$cantidad','$caracteristicas',1,'$cantidad','$ruta');";
							if(pg_query($conex,$sql))
							{
								echo "<script type='text/javascript'>
												if(confirm('Producto guardado, ¿desea seguir agregando productos?'))
												{
													location='agregar_p_o.php';
												}
												else 
												{
													location='inicio_ad.php';
												}
										 </script>";
							}
							else
							{
								echo "<script>
											alert('No se pudo guardar el producto, verifique sí el documento adjuntado es de tipo JPG o PNG.');
											location='".$_SERVER['HTTP_REFERER']."';
										</script>";
							}
						}
						else
						{
							echo"<script>alert('El archivo debe ser formato JPG o PNG.');													location=(".$_SERVER['HTTP_REFERER'].");
								</script>";
						}
					}
					//Si no se le adjunto una imagen
					elseif(array_sum($_FILES["imagen"]) == 4)
					{
						registrar_o_2($nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas);
					}
				}
	}
}
//Registrar miscelaneo sin imagen
function registrar_o_2($nombre,$ubicacion,$cantidad,$caracteristicas)
{
	global $conex;
	if(pg_query($conex,"INSERT INTO productos_o (id,nombre, ubicacion, cantidad, caracteristicas, estatus, patron) VALUES (DEFAULT,'$nombre','$ubicacion','$cantidad','$caracteristicas',1,'$cantidad');"))
	{
		echo "<script type='text/javascript'>
				if(confirm('El producto fue registrado sin un foto, es aconsejable que se le sea adjuntada una en el futuro, ¿desea seguir agregando productos?'))
				{
				location='agregar_p_o.php';
				}
				else 
				{
				location='inicio_ad.php';
				}
			</script>";
	}
	else
	{
		echo "<script>
				alert('No se pudo guardar el producto, verifique bien los datos insertados.');
				location='agregar_p_o.php';
			</script>";
	}
}
//Modificar miscelaneo con imagen
function modificar_o_1($id,$nombre,$ubicacion,$cantidad,array $imagen,$caracteristicas)
{
	global $conex;
	//1- Con imagen
	if(array_sum($_FILES["imagen"]) > 4)
	{
				//Verifica que el documento sea de tipo jpeg o png
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $imagen["tmp_name"]);
				if($mime == "image/jpeg" or $mime == "image/png")
				{
					//1- Se verifica con el patron
					$assoc = pg_fetch_assoc(pg_query($conex,"SELECT patron FROM productos_o WHERE id='$id'"));
					//Si el valor de entrada es menor, se mantiene el patron
					if($assoc["patron"]>=$cantidad)
					{
						$ruta = 'imagenes/'.$imagen['name'];
						move_uploaded_file($imagen['tmp_name'],$ruta);
						$sql = "UPDATE productos_o SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas', imagen='$ruta' WHERE id='$id';";
						if(pg_query($conex, $sql))
						{
							echo "<script type='text/javascript'>
										alert('Producto modificado exitosamente.');
										location='productos_a_o.php';
								</script>";
						}
						else
						{
							echo "<script>
									alert('No se pudo realizar conexión.');
									location='inicio_ad.php';
								</script>";
						}
					}
					//2- Si la cantidad de entrada es mayor a la del patron, se modifica el patron
					elseif($assoc["patron"]<$cantidad)
					{
						$ruta = 'fichas/'.$imagen['name'];
						move_uploaded_file($imagen['tmp_name'],$ruta);
						$sql = "UPDATE productos_o SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas', patron='$cantidad', imagen='$ruta' WHERE id='$id';";
						if(pg_query($conex, $sql))
						{
							echo "<script type='text/javascript'>
										alert('Producto modificado exitosamente.');
										location='productos_a_o.php';
								</script>";
						}
						else
						{
							echo "<script>
									alert('No se pudo realizar conexión.');
									location='inicio_ad.php';
								</script>";
						}
					}
					//3- Para advertir de error interno
					else
					{
						echo "<script>alert('ERROR OM13: Error interno en el sistema. Si el error persiste póngase en contacto con nosotros');
						location='".$_SERVER['HTTP_REFERER']."';
						</script>";
					}

				}
				else
				{
					 echo"<script>
				 			alert('El documento adjuntado debe ser de tipo PNG O JPG');
				 			location='".$_SERVER['HTTP_REFERER']."';
				 		</script>";
				}
			}
			//2- Sin imagen
			elseif(array_sum($_FILES["imagen"]) == 4)
			{
				modificar_o_2($id,$nombre,$estado,$ubicacion,$cantidad,$unidad,$caracteristicas);
			}
}
//Modificar miscelaneo sin imagen
function modificar_o_2($id,$nombre,$ubicacion,$cantidad,$caracteristicas)
{
	global $conex;
	if($nombre == null || $ubicacion == null || $caracteristicas == null)
	{
		pg_query($conex,"UPDATE productos_o SET cantidad='$cantidad' WHERE id='$id'");
	}
	else
	{
				//Se verifica con el patron
				$assoc = pg_fetch_assoc(pg_query($conex,"SELECT patron FROM productos_o WHERE id='$id'"));
				//1- Si la cantidad de entrada es menor al patron, el patron se mantiene
				if($assoc["patron"]>=$cantidad)
				{
					if(pg_query($conex,"UPDATE productos_o SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas' WHERE id='$id'"))
					{
						echo "<script type='text/javascript'>
									alert('El producto fue modificado.');
									location='productos_a_o.php';
							</script>";
					}
					else
					{
						echo "<script>
								alert('No se pudo realizar conexión.');
								location='inicio_ad.php';
							</script>";
					}
				}
				//2- Si la cantidad de entrada es mayor al patron, el patron se modifica
				elseif($assoc["patron"]<$cantidad)
				{
					$sql = "UPDATE productos_o SET nombre='$nombre', ubicacion='$ubicacion', cantidad='$cantidad', caracteristicas='$caracteristicas', patron='$cantidad' WHERE id='$id'";
					if(pg_query($conex, $sql))
					{
						echo "<script type='text/javascript'>
									alert('El producto fue modificado.');
									location='productos_a_o.php';
							</script>";
					}
					else
					{
						echo "<script>
								alert('No se pudo realizar conexión.');
								location='inicio_ad.php';
							</script>";
					}
				}
				//3- Para advertir de error interno
				else
				{
					echo"<script>alert('ERROR OM23: Error interno en el sistema. Si el error persiste póngase en contacto con nosotros');
					location='".$_SERVER['HTTP_REFERER']."';</script>";
				}
	}
}
//Registrar productos pedidos
function registrar_pedido($id_p,$nombre_p,$cantidad_p,$ubicacion_p,$tipo_p,$nombre_us,$apellido_us,$cedula_us,$correo_us)
{
	global $conex;
	if(pg_query($conex,"INSERT INTO registro VALUES (DEFAULT,'$id_p','$nombre_p','$cantidad_p','$ubicacion_p','$tipo_p','$nombre_us','$apellido_us','$cedula_us','$correo_us')"))
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>