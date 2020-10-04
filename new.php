
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/new.css">
	<title>Nuevo registro</title>
</head>
<body>
	<div id="contenedor">
	<header>
		<h1>Registro de usuario</h1>
	</header>
	<form action="" method="post">		
			<div id="formulario">
				<form action="" method="post">
					<p class="campo">Nombre de usuario: *</p>
						<input type="text" name="user"/><br/>
					<p class="campo">Contraseña: *</p>
						<input type="password" name="password"/><br/>
					<p class="campo">Email: *</p>
						<input type="text" name="email"/><br/>
					<p>*Requerido</p>
					<input type='submit' name='submit' value='Registrarse'>

<?php
require_once ('clase_usuarios.php');
if (isset($_POST['submit'])) {
	//Criamos um objeto da classe Senha e armazenamos o valor da senha criptografada na variável $pw
	/*Criamos um novo usuário que, em caso de conformidade com as verificações, executará o novo método() 
	para escrever seus dados para o banco de dados e redirecioná-lo para a página inicial*/
	$usuario = new Usuario($_POST['user'],$_POST['email']);
	$pw = $usuario -> encriptar($_POST['password']);
	if ($usuario->comprobaciones() !== false) {
		$usuario->nuevo();	
		header("Location:index1.php");
	}	
}
?>
					
				</form>
				<p id="link"><a href="index1.php">Volver a pantalla de inicio de sesión</a></p>
			</div>		
		</div>
</body>
</html>


