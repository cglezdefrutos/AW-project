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
                $queryData = $this->constructSearchQuery($filters);

                // Preparamos la consulta
                $stmt = $conn->prepare($queryData['query']);
                if(!$stmt)
                {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }

                // Asignamos los parametros solo si hay parámetros para enlazar
                if (!empty($queryData['params'])) 
                {
                    $stmt->bind_param($queryData['types'], ...$queryData['params']);
                }

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Asignamos los resultados a variables
                $stmt->bind_result($Id, $Name, $Description, $Price, $Location, $Date, $Capacity, $Category);

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


        public function registerEvent($eventDTO)
        {
            try {
                // Obtener la conexión a la base de datos
                $conn = application::getInstance()->getConnectionDb();
        
                // Preparar la consulta SQL para insertar un nuevo evento
                $stmt = $conn->prepare("INSERT INTO events (name, description, price, location, date, capacity, category, email_provider) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }
        
                // Asignar los parámetros
                $stmt->bind_param(
                    "ssississ",
                    $eventDTO->getName(),
                    $eventDTO->getDesc(),
                    $eventDTO->getPrice(),
                    $eventDTO->getLocation(),
                    $eventDTO->getDate(),
                    $eventDTO->getCapacity(),
                    $eventDTO->getCategory(),
                    $eventDTO->getEmailProvider()
                );
        
                // Ejecutar la consulta
                if (!$stmt->execute()) {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }
        
                // Cerrar la consulta
                $stmt->close();
        
            } catch (Exception $e) {
                error_log($e->getMessage());
                throw $e;
            }
        
            return true;
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

        private function constructSearchQuery($filters)
        {
            $query = "SELECT * FROM events WHERE ";
            $args = array();
            $types = '';

            foreach ($filters as $key => $value) {
                
                if($value == '')
                {
                    continue;
                }
            
                switch ($key) {
                    case 'name':
                        $query .= "name LIKE ? AND ";
                        $args[] = "%" . $this->realEscapeString($value) . "%";
                        $types .= 's';
                        break;
                    case 'start_date':  
                        $query .= "date >= ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 's';
                        break;  
                    case 'end_date':            
                        $query .= "date <= ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 's';
                        break;
                    case 'min_price':
                        $query .= "price >= ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 'd';
                        break;
                    case 'max_price':
                        $query .= "price <= ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 'd';
                        break;
                    case 'location':
                        $query .= "location LIKE ? AND ";
                        $args[] = "%" . $this->realEscapeString($value) . "%";
                        $types .= 's';
                        break;
                    case 'category':
                        $query .= "category = ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 's';
                        break;
                }
            }

            // Eliminar el último " AND " si hay filtros
            if (!empty($args)) 
            {
                $query = substr($query, 0, -4);
            } 
            // Si no hay filtros, eliminar la cláusula WHERE
            else 
            {
                $query = "SELECT * FROM events";
            }

            return array('query' => $query, 'params' => $args, 'types' => $types);
        }   

        
    }
?>