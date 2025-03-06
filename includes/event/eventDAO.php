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
                    $types = $queryData['types'];
                    $params = $queryData['params'];
                    $stmt->bind_param($types, ...$params);
                }

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Asignamos los resultados a variables
                $stmt->bind_result($Id, $Name, $Description, $Price, $Location, $Date, $Capacity, $Category, $EmailProvider);

                // Mientras haya resultados, los guardamos en el array
                while ($stmt->fetch())
                {
                    $event = new eventDTO($Id, $Name, $Description, $Date, $Price, $Location, $Category, $Capacity, $EmailProvider);
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
                $name = $eventDTO->getName();
                $description = $eventDTO->getDesc();
                $price = $eventDTO->getPrice();
                $location = $eventDTO->getLocation();
                $date = $eventDTO->getDate();
                $capacity = $eventDTO->getCapacity();
                $category = $eventDTO->getCategory();
                $emailProvider = $eventDTO->getEmailProvider();

                $stmt->bind_param("ssississ", $name, $description, $price, $location, $date, $capacity, $category, $emailProvider);
        
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
                $userId = $joinEventDTO->getUserId();
                $eventId = $joinEventDTO->getEventId();
                $userName = $joinEventDTO->getUserName();
                $userPhone = $joinEventDTO->getUserPhone();

                $stmt->bind_param("iisi", $userId, $eventId, $userName, $userPhone);

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

        public function getEventById($eventId)
        {
            $event = null;

            try {
                // Tomamos la conexion a la base de datos
                $conn = application::getInstance()->getConnectionDb();

                // Implementar la logica de acceso a la base de datos para obtener un evento por su id
                $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
                if(!$stmt)
                {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }

                // Asignamos los parametros
                $stmt->bind_param("i", $eventId);

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Asignamos los resultados a variables
                $stmt->bind_result($Id, $Name, $Description, $Price, $Location, $Date, $Capacity, $Category, $EmailProvider);

                // Si hay resultados, los guardamos en la variable $event
                if ($stmt->fetch())
                {
                    $event = new eventDTO($Id, $Name, $Description, $Date, $Price, $Location, $Capacity, $Category, $EmailProvider);
                }

                // Cerramos la consulta
                $stmt->close();

            } catch (Exception $e) {
                error_log($e->getMessage());
                throw $e;
            }

            return $event;
        }

        public function updateEvent($eventDTO)
        {
            try {
                // Tomamos la conexion a la base de datos
                $conn = application::getInstance()->getConnectionDb();

                // Implementar la logica de acceso a la base de datos para actualizar un evento
                $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, price = ?, location = ?, date = ?, capacity = ?, category = ? WHERE id = ?");
                if(!$stmt)
                {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }

                // Asignamos los parametros
                $name = $eventDTO->getName();
                $desc = $eventDTO->getDesc();
                $price = $eventDTO->getPrice();
                $location = $eventDTO->getLocation();
                $date = $eventDTO->getDate();
                $capacity = $eventDTO->getCapacity();
                $category = $eventDTO->getCategory();
                $id = $eventDTO->getId();

                $stmt->bind_param("ssissisi", $name, $desc, $price, $location, $date, $capacity, $category, $id);

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Cerramos la consulta
                $stmt->close();

            } catch (Exception $e) {
                error_log($e->getMessage());
                throw $e;
            }

            return true;
        }

        public function deleteEvent($eventId)
        {
            try {
                // Tomamos la conexion a la base de datos
                $conn = application::getInstance()->getConnectionDb();

                // Implementar la logica de acceso a la base de datos para eliminar un evento
                $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
                if(!$stmt)
                {
                    throw new Exception("Error al preparar la consulta: " . $conn->error);
                }

                // Asignamos los parametros
                $stmt->bind_param("i", $eventId);

                // Ejecutamos la consulta
                if(!$stmt->execute())
                {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }

                // Cerramos la consulta
                $stmt->close();

            } catch (Exception $e) {
                error_log($e->getMessage());
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
                    case 'capacity':
                        $query .= "capacity >= ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 'i';
                        break;
                    case 'category':
                        $query .= "category = ? AND ";
                        $args[] = $this->realEscapeString($value);
                        $types .= 's';
                        break;
                    case 'email_provider':
                        $query .= "email_provider = ? AND ";
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