<?php
	$conex = pg_connect("host='localhost' dbname=aljuarismi port=5432 user=postgres password=1234") or die ("Error de conexion ".pg_last_error());
?>