<?php

namespace TheBalance\event;

use TheBalance\user\userAlreadyJoinEventException;
use TheBalance\application;
use TheBalance\views\common\baseDAO;

/**
 * Data Access Object de eventos
 */
class eventDAO extends baseDAO implements IEvent
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Busca eventos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
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
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
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
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($Id, $Name, $Description, $Price, $Location, $Date, $Capacity, $CategoryId, $EmailProvider, $CategoryName);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $event = new eventDTO($Id, $Name, $Description, $Date, $Price, $Location, $Capacity, new eventCategoryDTO($CategoryId, $CategoryName), $EmailProvider);
                $events[] = $event;
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        // Devolvemos el array de eventos
        return $events;
    }

    /**
     * Registra un evento
     * 
     * @param array $eventDTO Datos del evento
     * 
     * @return bool Resultado del registro
     */
    public function registerEvent($eventDTO)
    {
        try {
            // Obtener la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();
    
            // Preparar la consulta SQL para insertar un nuevo evento
            $stmt = $conn->prepare("INSERT INTO events (name, description, price, location, date, capacity, category_id, email_provider) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }
    
            // Asignar los parámetros
            $name = $this->realEscapeString($eventDTO->getName());
            $description = $this->realEscapeString($eventDTO->getDesc());
            $price = $this->realEscapeString($eventDTO->getPrice());
            $location = $this->realEscapeString($eventDTO->getLocation());
            $date = $this->realEscapeString($eventDTO->getDate());
            $capacity = $this->realEscapeString($eventDTO->getCapacity());
            $categoryId = $this->realEscapeString($eventDTO->getCategoryId());
            $emailProvider = $this->realEscapeString($eventDTO->getEmailProvider());

            $stmt->bind_param("ssdssiis", $name, $description, $price, $location, $date, $capacity, $categoryId, $emailProvider);
    
            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
    
            // Cerrar la consulta
            $stmt->close();
    
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    
        return true;
    }

    /**
     * Comprueba si un usuario ya está apuntado a un evento
     * 
     * @param int $userId Id del usuario
     * @param int $eventId Id del evento
     * 
     * @return bool Resultado de la operación
     */
    public function isJoined($userId, $eventId)
    {
        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para comprobar si un usuario ya está apuntado a un evento
            $stmt = $conn->prepare("SELECT * FROM event_participants WHERE user_id = ? AND event_id = ?");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escUserId = $this->realEscapeString($userId);
            $escEventId = $this->realEscapeString($eventId);
            $stmt->bind_param("ii", $escUserId, $escEventId);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Almacenar el resultado para verificar el número de filas
            $stmt->store_result();

            // Si no se encontraron filas, el usuario no está apuntado al evento
            if ($stmt->num_rows === 0)
            {
                $stmt->close();
                return false;
            }

            // Cerramos la consulta
            $stmt->close();
        } 
        catch (\Exception $e) 
        {
            error_log($e->getMessage());
            throw $e;
        }

        return true;
    }

    /**
     * Apunta a un usuario a un evento
     * 
     * @param array $joinEventDTO Datos del usuario y evento
     * 
     * @return bool Resultado de la operación
     */
    public function joinEvent($joinEventDTO)
    {
        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para apuntar a un usuario a un evento
            $stmt = $conn->prepare("INSERT INTO event_participants (user_id, event_id, user_name, user_phone) VALUES (?, ?, ?, ?)");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $userId = $this->realEscapeString($joinEventDTO->getUserId());
            $eventId = $this->realEscapeString($joinEventDTO->getEventId());
            $userName = $this->realEscapeString($joinEventDTO->getUserName());
            $userPhone = $this->realEscapeString($joinEventDTO->getUserPhone());

            $stmt->bind_param("iisi", $userId, $eventId, $userName, $userPhone);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return true;
    }

    /**
     * Obtiene el evento asociado a un id
     * 
     * @param int $eventId Id del evento
     * 
     * @return eventDTO Evento
     */
    public function getEventById($eventId)
    {
        $event = null;

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para obtener un evento por su id
            $stmt = $conn->prepare(
                "SELECT e.*, c.name AS category_name
                 FROM events e
                 INNER JOIN event_categories c ON e.category_id = c.id
                 WHERE e.id = ?"
            );
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escEventId = $this->realEscapeString($eventId);
            $stmt->bind_param("i", $escEventId);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($Id, $Name, $Description, $Price, $Location, $Date, $Capacity, $CategoryId, $EmailProvider, $CategoryName);

            // Si hay resultados, los guardamos en la variable $event
            if ($stmt->fetch())
            {
                $event = new eventDTO($Id, $Name, $Description, $Date, $Price, $Location, $Capacity, new eventCategoryDTO($CategoryId, $CategoryName), $EmailProvider);
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $event;
    }

    /**
     * Comprueba si un usuario es propietario de un evento
     * 
     * @param int $eventId Id del evento
     * @param string $user_email Email del usuario
     * 
     * @return bool Resultado de la operación
     */
    public function ownsEvent($eventId, $userEmail)
    {
        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Comprobamos si el usuario es propietario del evento
            $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND email_provider = ?");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escEventId = $this->realEscapeString($eventId);
            $escUserEmail = $this->realEscapeString($userEmail);
            $stmt->bind_param("is", $escEventId, $escUserEmail);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Almacenar el resultado para verificar el número de filas
            $stmt->store_result();

            // Si no se encontraron filas, el evento no pertenece al usuario
            if ($stmt->num_rows === 0)
            {
                $stmt->close();
                return false;
            }

            // Cerramos la consulta
            $stmt->close();
        } 
        catch (\Exception $e) 
        {
            error_log($e->getMessage());
            throw $e;
        }

        return true;
    }

    /**
     * Obtiene los participantes de un evento
     * 
     * @param int $eventId Id del evento
     * 
     * @return array Participantes
     */
    public function getParticipants($eventId)
    {
        $participants = array();

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para obtener los participantes de un evento
            $stmt = $conn->prepare("SELECT * FROM event_participants WHERE event_id = ?");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escEventId = $this->realEscapeString($eventId);
            $stmt->bind_param("i", $escEventId);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($userId, $eventId, $userName, $userPhone);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $participant = new joinEventDTO($userId, $eventId, $userName, $userPhone);
                $participants[] = $participant;
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $participants;
    }

    /**
     * Actualiza un evento
     * 
     * @param array $eventDTO Datos del evento
     * 
     * @return bool Resultado de la operación
     */
    public function updateEvent($eventDTO)
    {
        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para actualizar un evento
            $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, price = ?, location = ?, date = ?, capacity = ?, category_id = ? WHERE id = ?");
            if(!$stmt)
            {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $name = $this->realEscapeString($eventDTO->getName());
            $desc = $this->realEscapeString($eventDTO->getDesc());
            $price = $this->realEscapeString($eventDTO->getPrice());
            $location = $this->realEscapeString($eventDTO->getLocation());
            $date = $this->realEscapeString($eventDTO->getDate());
            $capacity = $this->realEscapeString($eventDTO->getCapacity());
            $categoryId = $this->realEscapeString($eventDTO->getCategoryId());
            $id = $this->realEscapeString($eventDTO->getId());

            $stmt->bind_param("ssdssiii", $name, $desc, $price, $location, $date, $capacity, $categoryId, $id);

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

    /**
     * Elimina un evento
     * 
     * @param int $eventId Id del evento
     * 
     * @return bool Resultado de la operación
     */
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
            $escEventId = $this->realEscapeString($eventId);
            $stmt->bind_param("i", $escEventId);

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

    /**
     * Obtiene el id de una categoria por su nombre
     * 
     * @param string $categoryName Nombre de la categoria
     * 
     * @return int Id de la categoria|-1 si no se encuentra
     */
    public function getCategoryId($categoryName)
    {
        $categoryId = null;

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para obtener el id de una categoria por su nombre
            $stmt = $conn->prepare("SELECT id FROM event_categories WHERE name = ?");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escCategoryName = $this->realEscapeString($categoryName);
            $stmt->bind_param("s", $escCategoryName);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($categoryId);

            // Almacenar el resultado para verificar el número de filas
            $stmt->store_result();

            // Si no se encontraron filas, la categoria no existe
            if ($stmt->num_rows === 0)
            {
                $categoryId = -1;
            }
            // Si se encontró, obtenemos el id
            else
            {
                $stmt->fetch();
            }

            $stmt->close();            

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $categoryId;
    }

    /**
     * Registra una nueva categoria
     * 
     * @param string $categoryName Nombre de la categoria
     * 
     * @return int Id de la categoria registrada
     */
    public function registerCategory($categoryName)
    {
        $categoryId = null;

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para registrar una nueva categoria
            $stmt = $conn->prepare("INSERT INTO event_categories (name) VALUES (?)");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escCategoryName = $this->realEscapeString($categoryName);
            $stmt->bind_param("s", $escCategoryName);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Obtenemos el id de la categoria registrada
            $categoryId = $stmt->insert_id;

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $categoryId;
    }

    /**
     * Obtiene todas las categorias
     * 
     * @return array Categorias
     */
    public function getCategories()
    {
        $categories = array();

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para obtener todas las categorias
            $stmt = $conn->prepare("SELECT * FROM event_categories");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($id, $name);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $category = new eventCategoryDTO($id, $name);
                $categories[] = $category;
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $categories;
    }

    /**
     * Borra una categoria
     * 
     * @param int $categoryId Id de la categoria
     * 
     * @return bool Resultado de la operación
     */
    public function deleteCategory($categoryId)
    {
        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para borrar una categoria
            $stmt = $conn->prepare("DELETE FROM event_categories WHERE id = ?");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escCategoryId = $this->realEscapeString($categoryId);
            $stmt->bind_param("i", $escCategoryId);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return true;
    }
    
    /**
     * Construye la consulta SQL para buscar eventos en función de los filtros
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Datos de la consulta
     */
    private function constructSearchQuery($filters)
    {
        // Iniciar la consulta con un JOIN para incluir el nombre de la categoría
        $query = "SELECT e.*, c.name AS category_name 
                  FROM events e 
                  INNER JOIN event_categories c ON e.category_id = c.id 
                  WHERE ";
        $args = array();
        $types = '';

        foreach ($filters as $key => $value) {
            
            if($value == '')
            {
                continue;
            }
        
            switch ($key) {
                case 'name':
                    $query .= "e.name LIKE ? AND ";
                    $args[] = "%" . $this->realEscapeString($value) . "%";
                    $types .= 's';
                    break;
                case 'start_date':  
                    $query .= "e.date >= ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 's';
                    break;  
                case 'end_date':            
                    $query .= "e.date <= ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 's';
                    break;
                case 'min_price':
                    $query .= "e.price >= ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 'd';
                    break;
                case 'max_price':
                    $query .= "e.price <= ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 'd';
                    break;
                case 'location':
                    $query .= "e.location LIKE ? AND ";
                    $args[] = "%" . $this->realEscapeString($value) . "%";
                    $types .= 's';
                    break;
                case 'capacity':
                    $query .= "e.capacity >= ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 'i';
                    break;
                case 'category':
                    $query .= "c.name = ? AND "; // Filtrar por el nombre de la categoría
                    $args[] = $this->realEscapeString($value);
                    $types .= 's';
                    break;
                case 'email_provider':
                    $query .= "e.email_provider = ? AND ";
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
            $query = "SELECT e.*, c.name AS category_name 
                      FROM events e 
                      INNER JOIN event_categories c ON e.category_id = c.id";
        }

        return array('query' => $query, 'params' => $args, 'types' => $types);
    }   
}