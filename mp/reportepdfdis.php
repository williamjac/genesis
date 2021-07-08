<?php

	require_once("dompdf/dompdf_config.inc.php");
	session_start();
  include('../enlace/conexion.inc');
	include('../enlace/funcion.php');
	$dato=$_SESSION['usuario'];
	$ini=$_POST['txtfecini'];
	$fin=$_POST['txtfecfin'];

	$ar=idAreaxUsuario($dato);
	$consulta=mysqli_query(conectar(),"select ar.idarea, ar.area,lc.direccion,ar.telefono,ar.anexo,ar.correo,us.idusuario
	from usuario us, trabajador tb, area ar, local lc
	where us.idtrabajador=tb.idtrabajador and tb.idarea=ar.idarea and ar.idlocal=lc.idlocal and ar.idarea='$ar' and us.idusuario='$dato';");
	$fila=mysqli_fetch_assoc($consulta);

	$consar=mysqli_query(conectar(),"select nombre from anio where anio=year(curdate());");
	$resultado=mysqli_fetch_assoc($consar);
$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/stiloreporte.css">
<title>Reporte</title>
</head>
<body>
<center>
<div class="cabecera">
	<div class="logoperu">
		<img src="image/logoperu.jpg">
	</div>
	<div class="cbtexto">
		<h4>UNIVERSIDAD NACIONAL<br>SANTIAGO ANTUNEZ DE MAYOLO
			<h5>
				<em>"Una Nueva Universidad para el Desarrollo"<br><br>'
				.htmlentities($fila['area'], ENT_QUOTES,'UTF-8').'<br>'
					.htmlentities($fila['direccion'], ENT_QUOTES,'UTF-8').' Tel: '.$fila['telefono'].' - Anexo '.$fila['anexo'].'
					E-mail: '.htmlentities($fila['correo'], ENT_QUOTES,'UTF-8').'
				</em>
			</h5>
		</h4>
	</div>
	<div class="logofacebook">
		<img src="image/logofacebook.jpg">
	</div>
	<hr>
	<h5>
		"'.htmlentities($resultado['nombre'], ENT_QUOTES,'UTF-8').'"<br><br>
		RELACION DE DISTRIBUCION DE DOCUMENTOS
	</h5>
</div><br>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
		<th>EXP.</th>
		<th>FECHA</th>
	    <th class="clest">DOCUMENTO</th>
	    <th>ASUNTO</th>
	    <th>PASO A</th>
	    <th>FIRMA</th>
  </tr>';
	$query="select * from vta_rptdocdis where idarea='$ar' and (fechingsist>='$ini 00:00:00' and fechingsist<='$fin 23:59:59') and idestadoexp<3 and idderiva is not null order by expediente asc;";
	$resultado=mysqli_query(conectar(), $query);
	while($res=mysqli_fetch_assoc($resultado)){
$codigoHTML.='
	<tr>
		<td><p>'.$res['expediente'].' </p></td>
		<td><p>'.$res['fechingsist'].' </p></td>
		<td class="clest"><p>'.htmlentities($res['documento'], ENT_QUOTES,'UTF-8').' </p></td>
		<td><p>'.htmlentities($res['asunto'], ENT_QUOTES,'UTF-8').' </p></td>
		<td><p>'.$res['destino'].' </p></td>
		<td></td>
	</tr>';

}
$codigoHTML.='
</table>
</center>
</body>
</html>';
$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","128M");
$dompdf->render();
$dompdf->stream("Reportedis.pdf");
?>
