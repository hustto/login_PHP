<?php
require_once("clase_conectar.php");
class Usuario {
    //Declaramos as variáveis públicas da classe Usuario_clase, elas corresponderão aos campos na tabela de usuários do banco de dados de feedback
    public $nombre_usuario;
	public $contrasena;
    public $email;
    public $nombre_origen;
    public $email_origen;
    public $asunto;
    public $mensaje;
    public $formato;
    public $headers;
    public $objetoConexion;
	//Declaro o método construtor da classe Usuario_clase para a qual passamos as variáveis da própria classe
	public function __construct($nombre_usuario,$email) {
        $this->nombre_usuario=$nombre_usuario;
		$this->email=$email;
        $this->nombre_origen = "admin";
        $this->email_origen = "root@localhost";
        $this->asunto = "Usuario creado en ejercicio feedback";
        $this->mensaje = "Su cuenta con nombre de usuario:".$this->nombre_usuario."se ha creado correctamente.";
        $this->formato = "html";
        $this->objetoConexion=new Conectar('mysql:host=localhost;dbname=feedback','root','');
    }

    //Função que impedirá o usuário de criar se ele não atender às condições
    public function comprobaciones () {
        $this->objetoConexion->conectar();
        //Verificamos se os campos não estão vazios.
        if ($this->nombre_usuario == '' || $this->contrasena == '' || $this->email == '') {
            echo "<div id='msg'>Por favor, introduce todos los campos requeridos</div>";
            return false;
        }
        //Verificamos se o endereço de e-mail é válido
        elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            echo "<div id='msg'><br/>La dirección de correo electrónico <i>".$this->email."</i> es inválida. Por favor, introduzca una correcta.</div>";
            return false;
        }
        //Verificamos se o nome ainda não está registrado
        else {
            //Consultamos os registros e seu valor na coluna do usuário e os armazenamos no $rw
            $rw = $this->objetoConexion->consultar("SELECT user from usuarios");
            if (count($rw)) {
                    //Passamos pela matriz e caso o nome inserido corresponda a um já registrado, impedirá a inscrição
                    for ($i=0;$i<count($rw);$i++) {
                        if ($rw[$i]['user'] == $this->nombre_usuario) {
                            echo "<div id='msg'>Nombre de usuario ya registrado. Por favor elija otro.</div>";
                            return false;
                            break;
                        }
                    }
            }
        }
    }
    //Função que registra dados do usuário no banco de dados
    public function nuevo() {     
        try {
            $this->objetoConexion->conectar();
            $this->objetoConexion->ejecutar("INSERT usuarios SET user='$this->nombre_usuario', password='$this->contrasena', email='$this->email'");
            //Cria um objeto de e-mail cujos parâmetros são o nome de usuário e o e-mail inseridos e, em seguida, chamar o método enviar() e enviar-lhe o e-mail
            $this->enviar();
            $this->objetoConexion->desconectar();
        }
        catch (PDOException $ex) {
            //Retorna a exceção se o usuário não puder ser inserido
            throw $ex;
        }
        $this->objetoConexion->desconectar();
    }

    //Método que envia o e-mail e retorna um erro se não for possível
    public function enviar() {
        $this->headers = "To: ".$this->nombre_usuario ."<".$this->email."> \r \n";
        $this->headers .= "From: ".$this->nombre_origen." <".$this->email_origen."> \r \n";
        $this->headers .= "Return-path: <".$this->email_origen."> \r \n";
        $this->headers .= "Reply-to: ".$this->email_origen ."\r \n";
        $this->headers .= "MIME-Version: 1.0 \r \n";
        if ($this->formato == "html") {
            $this->headers .= "Content-Type: text/html; charset = utf-8 \r \n";
        }
        else {
            $this->headers .= "Content-Type: text/plain; charset = utf-8 \r \n";
        }
        
        if (@mail ($this->email, $this->asunto, $this->mensaje, $this->headers)) {
            echo "Su correo se envió";
        }
        else {
            echo "Error al enviar correo";
        }
    }

    public function encriptar($enc){
        $this->objetoConexion->conectar();
        $pass = password_hash($enc, PASSWORD_DEFAULT);
        $this->contrasena=$pass;
        return $this->contrasena;
        $this->objetoConexion->desconectar();
    }

    public function verificar($user,$pass){
        try {   
            $this->objetoConexion->conectar();
            //Coletamos todas as linhas com colunas de usuário e senha e as armazenamos no $rw
            $rw = $this->objetoConexion->consultar("SELECT user, password FROM usuarios");
            if(count($rw)) {
                //Recorremos todas las filas del array
                for ($i=0;$i<count($rw);$i++) {
                    /*Se o usuário entrou corresponde um armazenado no banco de dados e o password_verify confirmado 
                    que a senha inserida corresponde ao hash do armazenado, a função retorna verdadeira para que o usuário possa logar*/
                    if ($rw[$i]['user'] == $user && password_verify($pass,$rw[$i]['password'])) {
                        echo "Contraseña correcta";
                        return true;
                        break;
                    }
                }
            }
            $this->objetoConexion->desconectar();
        }
        catch (PDOException $ex) {
            throw $ex;
        }
    }
}


?>