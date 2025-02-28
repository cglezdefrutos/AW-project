<?php
    require_once("IEvent.php");
    require_once("eventDTO.php");
    require_once(__DIR__ . "/../views/common/baseDAO.php");

    class eventDAO extends baseDAO implements IEvent
    {
        public function __construct()
        {

        }
        
        public function getEvents($filters = array() )
        {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para obtener los eventos que cumplan con los filtros pasados como parametro
            $query = constructQuery($filters);

            // Preparamos la consulta
            $stmt = $conn->prepare($query[0]);

            // Asignamos los parametros
            $stmt->bind_param(str_repeat("s", count($query[1])), ...$query[1]);

            // Ejecutamos la consulta
            $stmt->execute();

            // Asignamos los resultados a variables
            $stmt->bind_result($Id, $Name, $Description, $Date, $Price, $Location, $Category);

            // Creamos un array para guardar los eventos
            $events = array();

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $event = new eventDTO($Id, $Name, $Description, $Date, $Price, $Location, $Category);
                $events[] = $event;
            }

            // Cerramos la consulta
            $stmt->close();

            // Devolvemos el array de eventos
            return $events;
        }

        private function constructQuery($filters)
        {
            $query = "SELECT * FROM eventos WHERE ";
            $args = array();

            foreach ($filters as $key => $value) {
                $query .= $key . " = ? AND ";
                $args[] = realEscapeString($value);
            }

            $query = substr($query, 0, -4);

            return array($query, $args);
        }   
        
    }
?>