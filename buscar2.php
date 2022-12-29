<?php
    session_start();
    error_reporting(0);
    include 'conex.php';
    $salida = "";
    $query = "SELECT * FROM productos_v WHERE estatus=1 ORDER BY nombre";
    if(isset($_POST['consulta'])) 
    {
        $q = pg_escape_string($conex,$_POST['consulta']);
        $query = "SELECT * FROM productos_v WHERE nombre LIKE '%$q%' AND estatus=1 ORDER BY nombre";
    }
    $resultado = pg_query($conex,$query);
    if(pg_num_rows($resultado)>0) 
    {
        if($_SESSION['tipo_de_usuario'] == 'admin')
        {
            $salida.="<table border=1 class='tabla_datos'>
                    <thead>
                        <tr id='titulo'>
                            <th>Imagen</th>
                            <th id='nom'>Nombre</th>
                            <th>Ubicación</th>
                            <th>Características</th>
                            <th>Cantidad</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
            <tbody>";
            while ($fila = pg_fetch_assoc($resultado)) 
            {
                $parte1 = $fila["patron"]/3;
                $parte2 = 2*$fila["patron"]/3;
                $salida.="<tr>
                                <th><a href=".$fila['imagen']." data-fancybox><image src=".$fila['imagen']." style='width:100px; height:auto;'><a></th>
                                <th>".$fila['nombre']."</th>
                                <td>".$fila['ubicacion']."</td>
                                <td>".$fila['caracteristicas']."</td>";
                                //Menor a un tercio de la cantidad establecida
                                if($fila['cantidad']<=$parte1)
                                {
                                $salida.="<td style='background-color:red;'>".$fila['cantidad']."</td>";
                                }
                                //Mayor a un tercio de la cantidad establecida pero menor a dos terceras partes de la misma
                                elseif ($fila['cantidad']>$parte1 and $fila['cantidad']<$parte2) 
                                {
                                $salida.="<td style='background-color:orange'>".$fila['cantidad']."</td>";
                                }
                                //Mayor a las dos terceras partes de la cantidad establecida
                                else
                                {
                                $salida.="<td style='background-color:green;'>".$fila['cantidad']."</td>";
                                }
                      $salida.="<td><button onclick={location='modificar_p_v.php?id=".$fila['id']."'}>Modificar</button></td>
                                <td>
                                    <form method='POST' action='borrar.php'>
                                        <button type='submit'>Eliminar</button>
                                        <input type='hidden' name='type' value='v'>
                                        <input type='hidden' name='id' value=".$fila['id'].">
                                    </form>
                                </td>
                        </tr>";
            }
            $salida.="</tbody></table>";
        }
        elseif($_SESSION['tipo_de_usuario'] == 'usuario' or $_SESSION["tipo_de_usuario"] == "super-usuario")
        {
            $salida.="<table border=1 class='tabla_datos'>
                    <thead>
                        <tr id='titulo'>
                            <th>Imágen</th>
                            <th id='nom'>Nombre</th>
                            <th>Ubicación</th>
                            <th>Características</th>
                            <th>Cantidad</th>
                            <th><a href='cart.php' style='text-decoration:none; color:#ffdb4d;'>Ver pedido <i class='icon-redo2'></i></a></th>
                        </tr>
                    </thead>
            <tbody>";
            while ($fila = pg_fetch_assoc($resultado)) 
            {
                //Para evitar que al hacer pedido se mezclen los id de otros productos y activar la opcion de 'Agregado'
                $found = false;
                if(isset($_SESSION["cart"]))
                {
                    foreach($_SESSION["cart"] as $c)
                    {
                        if($c["product_id"] == $fila["id"] && $c["type"] == "v")
                        {
                            $found=true;
                            break;
                        }
                    }
                }
                $salida.="<tr>
                                <th><a href=".$fila['imagen']." data-fancybox><image src=".$fila['imagen']." style='width:100px; height:auto;'><a></th>
                                <th>".$fila['nombre']."</th>
                                <td>".$fila['ubicacion']."</td>
                                <td>".$fila['caracteristicas']."</td>
                                <td>".$fila['cantidad']."</td>";
                        if($found)
                        {
                            $salida.="<td>
                                    <a href='cart.php' class='ref' style='text-decoration:none; color:black;'>Agregado </a>
                                    <a href='delfromcart.php?id=".$c['product_id']."'><input type ='image' src='css/image/cross_40.png' valign='-5px'></a>
                                    </td>";
                        }
                        else
                        {
                            $salida.="<td>
                                        <form method ='post' action='addtocart.php'>
                                            <input type='hidden' name='product_id' value=".$fila['id'].">
                                            <input type='number' name='q' min='1' placeholder='Cantidad' required style='width:100%;'>
                                            <input type='hidden' name='type' value='v'>
                                            <button type='submit' style='width:100%;'>Agregar pedido</button>
                                        </form>
                                    </td>";
                        }
                $salida.="</tr>";
            }
            $salida.="</tbody></table>";
        }
    }
    else
    {
        $salida.="<p id=not>No hay resultados.</p>";
    }
    echo $salida;
?>