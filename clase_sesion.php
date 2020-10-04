<?php
	class Sesion {
		//Iniciamos la sesión
		function Sesion () {
			session_start();
		}

		//Método para registro de variáveis de sessão. Vamos usá-lo para salvar o nome de usuário
		public function set($nombre,$valor) {
			$_SESSION[$nombre] = $valor;
		}

		//Recupera o valor do nome de usuário
		public function get($user) {
			if (isset($_SESSION[$user])) {
				return $_SESSION[$user];
			} else {
				return false;
			}
		}

		//Fecha a sessão e retorna à página inicial
		public function borrar_sesion() {
			$_SESSION = array();
			session_destroy();
			header("Location: index1.php");
		}
	}
?>
