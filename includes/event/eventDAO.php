<?php
    require_once("IEvent.php");
    require_once("eventDTO.php");
    require_once(__DIR__ . "/../views/common/baseDAO.php");
    require(__DIR__ . "/../user/userAlreadyJoinEventException.php");

    class eventDAO extends baseDAO implements IEvent
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function getEvents($filters = array() )
        {
            $events = array();

            try {
                // Tomamos la conexion a la base de datos
                $conn = application::getInstance()->getConnectionDb();

                // Implementar la logica de acceso a la base de datos para obtener los eventos que cumplan con los filtros pasados como parametro
                $query = $this->constructQuery($filters);

                // Preparamos la consulta
                $stmt = $conn->prepare($query[0]);
                if(!$stmt)
                {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }

                // Asignamos los parametros
                $stmt->bind_param(str_repeat("s", count($query[1])), ...$query[1]);

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Asignamos los resultados a variables
                $stmt->bind_result($Id, $Name, $Description, $Date, $Price, $Location, $Category);

                // Mientras haya resultados, los guardamos en el array
                while ($stmt->fetch())
                {
                    $event = new eventDTO($Id, $Name, $Description, $Date, $Price, $Location, $Category);
                    $events[] = $event;
                }

                // Cerramos la consulta
                $stmt->close();

            } catch (Exception $e) {
                error_log($e->getMessage());
                throw $e;
            }

            // Devolvemos el array de eventos
            return $events;
        }

        public function joinEvent($joinEventDTO)
        {
            try {
                // Tomamos la conexion a la base de datos
                $conn = application::getInstance()->getConnectionDb();

                // Implementar la logica de acceso a la base de datos para apuntar a un usuario a un evento
                $stmt = $conn->prepare("INSERT INTO event_participants (user_id, event_id, user_name, user_phone) VALUES (?, ?, ?, ?)");
                if(!$stmt)
                {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }

                // Asignamos los parametros
                $stmt->bind_param("iisi", $joinEventDTO->getUserId(), $joinEventDTO->getEventId(), $joinEventDTO->getUserName(), $joinEventDTO->getUserPhone());

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Cerramos la consulta
                $stmt->close();

            } catch (Exception $e) {
                
                error_log($e->getMessage());

                if($conn->sqlstate == 23000 || $conn->errno == 1062)
                {
                    throw new userAlreadyJoinEventException("El usuario ya está apuntado al evento");
                }

                throw $e;
            }

            return true;
        }

        private function constructQuery($filters)
        {
            $query = "SELECT * FROM eventos WHERE ";
            $args = array();

            foreach ($filters as $key => $value) {
                $query .= $key . " = ? AND ";
                $args[] = $this->realEscapeString($value);
            }

            $query = substr($query, 0, -4);

            return array($query, $args);
        }   
        
    }
?>