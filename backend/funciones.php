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

    function AgregarBici($usuarioId, $marca, $modelo, $estacionId) {
        // Preparar la consulta SQL para insertar una nueva bicicleta
        $query = "INSERT INTO bicicletas (marca, modelo, propietario_id, estacion_id, hora_entrada) 
                VALUES (:marca, :modelo, :propietario_id, :estacion_id, NOW())";

        // Preparar la sentencia
        $statement = $this->db->prepare($query);

        // Vincular parámetros
        $statement->bindParam(':marca', $marca);
        $statement->bindParam(':modelo', $modelo);
        $statement->bindParam(':propietario_id', $usuarioId);
        $statement->bindParam(':estacion_id', $estacionId);

        // Ejecutar la consulta
        $statement->execute();
    }

    function QuitarBici($bicicletaId) {
        // Preparar la consulta SQL para eliminar la bicicleta del usuario
        $query = "DELETE FROM bicicletas WHERE id = :bicicleta_id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':bicicleta_id', $bicicletaId);
    
        // Ejecutar la consulta
        $statement->execute();
    }

	//inicia sesion
    function logIn($email,$pass){

        //hago la sentencia sql
        $sql = "SELECT * FROM usuarios WHERE email= :email AND password=:pass";
        $consulta = $this->db->prepare($sql);
        $consulta->bindParam(':email', $email);
        $consulta->bindParam(':pass', $pass);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        //si hay una coincidencia, significa que email y contraseña es correcto y el length del array es mayor que 0
        if(sizeof($resultado) > 0){
            return true;
        }else{
            return false;
        }
    }

    function subscribirse($id,$option){
        
        //option puede ser de 1 dia, 1 semana o 1 mes, recogiendo la palabra dia, semana o mes

        $fecha_actual = date('Y-m-d H:i:s');

        switch ($option) {
            case 'dia':
                $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha_actual . '+1 day'));
                break;
            case 'semana':
                $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha_actual . '+1 week'));
                break;
            case 'mes':
                $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha_actual . '+1 month'));
                break;
            default:
                // Si la opción no es válida, no se realizará ninguna actualización
                return false;
        }

        // Construir la consulta SQL
        $sql = "UPDATE usuarios SET fecha_subscripcion = :fecha_nueva WHERE id = :usuario_id";

        // Preparar la consulta
        $consulta = $this->db->prepare($sql);

        // Asignar los parámetros y ejecutar la consulta
        $consulta->bindParam(':fecha_nueva', $fecha_nueva);
        $consulta->bindParam(':usuario_id', $id);
        $consulta->execute();
    }

    function libreEstacion($id_estacion){

        $sql = "SELECT * FROM bicicletas WHERE estacion_id= :estac";
        $consulta = $this->db->prepare($sql);
        $consulta->bindParam(':estac', $id_estacion);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $bicisDentro = sizeof($resultado);

        $sql = "SELECT * FROM estacion WHERE estacion_id= :estac";
        $consulta = $this->db->prepare($sql);
        $consulta->bindParam(':estac', $id_estacion);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if($bicisDentro < $resultado[0]['capacidad']){
            return true;
        }else{
            return false;
        }

    }

    function modificarBicisEstacion($id_bici,$id_estacion,$option)
    {
        if($this->libreEstacion($id_estacion)){

            $sql = "UPDATE bicicletas SET estacion_id = :id_est WHERE id = :bici_id";

            // Preparar la consulta
            $consulta = $this->db->prepare($sql);

            // Asignar los parámetros y ejecutar la consulta
            $consulta->bindParam(':bici_id', $id_bici);

            if($option==0){
                $id_estacion = null;
            }
            
            $consulta->bindParam(':id_est', $id_estacion);
            $consulta->execute();
        }
    }
}
    

?>