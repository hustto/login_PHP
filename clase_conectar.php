<?php
class Conectar {
    //Declamando as variáveis privadas da classe que correspondem aos parâmetros de conexão ao servidor
	private $servidor;
    private $user;
    private $password;
	//Eu declaro a variável que é o assunto da conexão
    private $objetoConexion;	
	//Declaro o método construtor da classe, para o qual passo as variáveis de conexão para o servidor
    public function __construct($servidor,$user,$password) {
        //Eu defina o valor de cada variável na classe para o valor que passei para o contrutor ao fazer a chamada no momento da definição da classe
		$this->servidor=$servidor;
        $this->user=$user;
        $this->password=$password;
    }
	
	//Método para  fazer a conexão com o servidor.
    public function conectar() {
        //Usando a tentativa tentar tentar fazer o que é indicado entre suas chaves
		try {
            //Fazemos a conexão usando a classe PDO 
			$this->objetoConexion = new PDO($this->servidor,  $this->user ,  $this->password );
            //Definimos os atributos correspondentes em caso de erro 
			$this->objetoConexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        //Caso a tentativa não funcione, declaramos a ex variável do PDOException
		catch(PDOException $ex) {
            echo "Problemas al conectar con la base de datos";
        }
    }
    
	//Método para acabar com a conexão com o banco de dados
	public function desconectar() {
        //Nós combinamos o objeto de conexão com nulo
		$this->objetoConexion=null;
    }
    
	//Método para execução da instrução SQL passou como parâmetro de função
	public function ejecutar($strComando) {
        try {
            //A variável Executar é um objeto que instancia a classe de objeto Conexão da classe clase_conexion
			//Prepara a instrução SQL limpando possíveis problemas relacionados à injeção SQL. 
			$ejecutar=$this->objetoConexion->prepare($strComando); 
            //Executa a declaração passada a ela como parâmetro strComando.
			$ejecutar->execute();		
        }
        catch(PDOException $ex) {
            //Retorna a variável ex com a exceção que surge
			throw $ex;
        }
    }

    //Método para armazenar dados retornados por consultas feitas ao banco de dados
    public function consultar($strComando) {
        try {
            $ejecutar=$this->objetoConexion->prepare($strComando); 
            //Executa a declaração passada a ela como parâmetro strComando. (http://php.net/manual/es/pdostatement.execute.php)
            $ejecutar->execute();
            //Economizamos para a variável de linha o que a função fetchAll retorna, ou seja, a consulta SQL. (http://php.net/manual/es/pdostatement.fetchall.php)
            $rows = $ejecutar->fetchAll();
            return $rows;

        }
        catch(PDOException $ex) {
            //Retorna a variável ex com a exceção que surge
            throw $ex;
        }
    }
}







?>