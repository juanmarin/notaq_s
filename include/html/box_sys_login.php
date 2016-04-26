<html>
	<head>
		<title>Iniciar sesion :: Sistema Integral de Proyectos</title>
		<meta name="generator" content="Bluefish 2.0.1" >
		<meta name="author" content="Akmmon" >
		
		<meta name="keywords" content="Noticias">
		<meta name="description" content="noticias">
		<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
		<meta http-equiv="content-style-type" content="text/css">

		<link rel="stylesheet" href="estilo/box_login.css" type="text/css" media="all" />
		
		<script type="text/javascript" >
			$("#usuario").focus();
		</script>
	</head>
	<body>
			<div id="BOXCONTENIDO">
				<form action="?pg=<?php echo ($_GET['pg'])? $_GET['pg'] : 1; ?>" method="post">
				<table class="log">
					<thead>
						<tr><th>Iniciar sesión</th></tr>
					</thead>
					<tbody>
						<tr><td><?php echo $msg; ?></td></tr>
						<tr><th valign="bottom">Nombre de usuario:</th></tr>
						<tr><td valign="top"><input type="text" name="uname" id="usuario" /></td></tr>
						<tr><th valign="bottom">Contraseña:</th></tr>
						<tr><td valign="top"><input type="password" name="pwd" /></td></tr>
						<tr><th>Recordar mi sesión:<input type="checkbox" name="remember" value="1" /></td></tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2"><input type="submit" value="Enviar" id="enviar" /></th>
						</tr>
					</tfoot>
				</table>
				</form>
			</div>
	</body>
</html>