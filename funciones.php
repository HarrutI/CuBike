<?php


define("SERVIDOR", "localhost");
define("USUARIO", "root");
define("CLAVE", "");

class estacion
{
    protected $db;

	
    function __construct($BD="") //Esto es el constructor
  	{  	  
	    /* Intentamos establecer una conexión con el servidor.*/
		try {
			if ($BD!='')
				$this->db = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . $BD .";charset=utf8", USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			else 
				$this->db = new PDO("mysql:host=" . SERVIDOR. ";charset=utf8", USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			
			$this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,  true);
			// Indicamos como atributo que se debe devolver una cadena vacía para los valores nulos
			$this->db->setAttribute(PDO::NULL_TO_STRING, true);
			// Si no indicamos la BD es que hay que crearla de nuevo
			if ($BD=='') {
				// Ejecutamos la SQL de Creación de BD directamente
			   // en el servidor MySQL.
			   /* Intentamos crear la base de datos "ejercicios".
				* Si se consigue hacerlo, se informa de ello.
			   * Si no, también se informa y se indica cuál es el
			   * motivo del fallo con el mensaje de error.*/
			   $sql = file_get_contents('DataBase.sql');			 
			   $this->ejecuta_SQL($sql);
			}
		} catch (PDOException $e) {
			die ("<p><H3>No se ha podido establecer la conexión.
				  <P>Compruebe si está activado el servidor de bases de 
				  datos MySQL.</H3></p>\n <p>Error: " . $e->getMessage() . "</p>\n");
		} // end try
	}//end function constructor

    function __destruct() //Esto es el destructor
	{
		if (isset($db)) // Desconectamos de la BD
			$db=null;
	}//end destructor agenda
		
	  

	//Función para ejecutar las consultas SQL
    function ejecuta_SQL($sql) {
		$resultado=$this->db->query($sql);
		//Si no obtiene el resultado, salta el error mediante el echoy muestra el fallo con print_r
		if (!$resultado)
		{
			echo"<H3>No se ha podido ejecutar la consulta: <PRE>$sql</PRE><P><U> Errores</U>: </H3><PRE>";
			print_r($this->db->errorInfo());					
			die ("</PRE>");
		}
		return $resultado;
	} // end ejecuta_SQL

    function crearCuenta($nombre,$apel,$password,$email){

        $sql = "INSERT INTO usuarios (nombre, apellido, password, email) VALUES (:nombre, :apellido, :password, :email)";

        $consulta = $this->db->prepare($sql);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':apellido', $apel);
        $consulta->bindParam(':password', $password);
        $consulta->bindParam(':email', $email);
        $consulta->execute();
    }
}
    function AgregarBici($usuarioId, $marca, $modelo, $estacionId, $conexion) {
    // Preparar la consulta SQL para insertar una nueva bicicleta
    $query = "INSERT INTO bicicletas (marca, modelo, propietario_id, estacion_id, hora_entrada) 
              VALUES (:marca, :modelo, :propietario_id, :estacion_id, NOW())";

    // Preparar la sentencia
    $statement = $conexion->prepare($query);

    // Vincular parámetros
    $statement->bindParam(':marca', $marca);
    $statement->bindParam(':modelo', $modelo);
    $statement->bindParam(':propietario_id', $usuarioId);
    $statement->bindParam(':estacion_id', $estacionId);

    // Ejecutar la consulta
    $statement->execute();
}

    function QuitarBici($bicicletaId, $conexion) {
        // Preparar la consulta SQL para eliminar la bicicleta del usuario
        $query = "DELETE FROM bicicletas WHERE id = :bicicleta_id";
        $statement = $conexion->prepare($query);
        $statement->bindParam(':bicicleta_id', $bicicletaId);
    
        // Ejecutar la consulta
        $statement->execute();
    }

?>
