<?php
session_start();
//error_reporting(0);
require 'conex.php';
if($_SESSION["tipo_de_usuario"] == "super-usuario")
{
    $salida = "";
    $query = "SELECT * FROM usuarios ORDER BY id";
    if(isset($_POST['consulta'])) 
    {
        $q = pg_escape_string($conex,$_POST['consulta']);
        $query = "SELECT * FROM usuarios WHERE nombre LIKE '%$q%' ORDER BY nombre";
    }
        $resultado = pg_query($conex,$query);
    if(pg_num_rows($resultado)>0) 
    {
            $salida.="<table border=1 class='tabla_datos'>
                    <thead>
                        <tr id='titulo'>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cedula</th>
                            <th>Correo</th>
                            <th>Tipo de usuario</th>
                            <th>Estatus</th>
                            <th colspan='2'></th>
                        </tr>
                    </thead>
            <tbody>";
            while($fila = pg_fetch_assoc($resultado)) 
            {
                $found=false;
                if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"]))
                {
                        if($_SESSION["user_id"] == $fila["id"])
                        {
                            $found=true;
                        }
                }
                if($found)
                {
                    $salida.="<tr id='modificar'>
                                <form method='POST' action='php/us_modify.php'>
                                    <td>".$fila['id']."</td>
                                    <td><input type='text' class='modify' id='nombre' name='nombre' value='".$fila['nombre']."'></td>
                                    <td><input type='text' class='modify' id='apellido' name='apellido' value='".$fila['apellido']."'></td>
                                    <td><input type='number' class='modify' id='cedula' name='cedula' value='".$fila['cedula']."'></td>
                                    <td><input type='text' class='modify' id='correo' name='correo' value='".$fila['correo']."'></td>
                                    <td><input type='text' class='modify' id='tipo_usuario' name='tipo_usuario' value='".$fila['tipo_usuario']."'></td>
                                    <td></td>
                                    <td colspan='2'>
                                        <input type='submit' style='width:100%;' value='Guardar'>
                                        <input type='hidden' name='modify' value='2'> 
                                    </td>
                                </form>";
                }
                else
                {
                    $salida.="<tr>
                                    <td>".$fila['id']."</td>
                                    <th>".$fila['nombre']."</th>
                                    <th>".$fila['apellido']."</th>
                                    <th>".$fila['cedula']."</th>
                                    <th>".$fila['correo']."</th>
                                    <td>".$fila['tipo_usuario']."</td>";
                            if($fila["estatus"] == 1)
                            {
                                $salida.="<td>Activo</td>";
                            }
                            elseif($fila["estatus"] == 0) 
                            {
                                $salida.="<td>Inactivo</td>";
                            }
                            //Para retirar a un usuario o reactivarlo
                            if($fila["estatus"] == 0)
                            {
                                $salida.="<th>
                                        <form method='POST' action='php/del_usuario.php'>
                                            <input type='hidden' name='user_id' value=".$fila['id'].">
                                            <button type='submit'>Activar de nuevo</button>
                                       </form>
                                        </th>";    
                            }
                            else
                            {
                                $salida.="<th>
                                        <form method='POST' action='php/del_usuario.php'>
                                            <input type='hidden' name='user_id' value=".$fila['id'].">
                                            <button type='submit'>Dar de baja</button>
                                        </form>
                                        </th>";
                            }

                    //Para modificar los datos del usuario
                    $salida.="<th>
                            <form method='POST' action='php/us_modify.php'>
                                <input type='hidden' name='user_id' value=".$fila['id'].">
                                <input type='hidden' name='modify' value='1'>
                                <input type='submit' value='Modificar'>
                            </form>
                            </th>";
                }
            }
       $salida.="</tr></tbody></table>";
    }
    else
    {
        $salida.="<p id=not>No existe usuarios registrados con ese nombre.</p>";
    }
    echo $salida;
}