<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseDAO;
use TheBalance\application;

/**
 * Data Access Object para planes de entrenamiento
 */
class planDAO extends baseDAO implements IPlan
{
    /**
     * Busca planes de entrenamiento aplicando filtros
     * 
     * @param array $filters Filtros de búsqueda
     * @return array Lista de planDTO
     */
    public function searchTrainingPlans($filters = array())
    {
        $plans = array();

        try {
            $conn = application::getInstance()->getConnectionDb();
            $queryData = $this->buildSearchQuery($filters);

            $stmt = $conn->prepare($queryData['query']);
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            if (!empty($queryData['params'])) {
                $stmt->bind_param($queryData['types'], ...$queryData['params']);
            }

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $stmt->bind_result(
                $id,
                $trainer_id,
                $name,
                $description,
                $difficulty,
                $duration,
                $price,
                $pdf_path,
                $image_guid,
                $created_at
            );

            while ($stmt->fetch()) {
                $plans[] = new planDTO(
                    $id,
                    $trainer_id,
                    $name,
                    $description,
                    $difficulty,
                    $duration,
                    $price,
                    $image_guid,
                    $pdf_path,
                    $created_at
                );
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $plans;
    }

    /**
     * Obtiene un plan específico por su ID
     * 
     * @param int $id ID del plan
     * @return planDTO Datos del plan
     */
    public function getPlanById($id)
    {
        $plan = null;

        try {
            $conn = application::getInstance()->getConnectionDb();
            $query = "SELECT * FROM training_plans WHERE id = ?";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            $stmt->bind_param('i', $id);

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $stmt->bind_result(
                $id,
                $trainer_id,
                $name,
                $description,
                $difficulty,
                $duration,
                $price,
                $pdf_path,
                $image_guid,
                $created_at
            );

            if ($stmt->fetch()) {
                $plan = new planDTO(
                    $id,
                    $trainer_id,
                    $name,
                    $description,
                    $difficulty,
                    $duration,
                    $price,
                    $image_guid,
                    $pdf_path,
                    $created_at
                );
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $plan;
    }

    /**
     * Obtiene la lista de pedidos de un usuario
     * 
     * @param int $userId ID del usuario
     * @return array Lista de orderDTO
     */
    
     public function getPlansByUserId($userId) {

        $plans = array();
    
        try {
            // Conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();
    
            // Consulta: planes que ha comprado el cliente
            $stmt = $conn->prepare("
                SELECT tp.id, tp.trainer_id, tp.name, tp.description, tp.difficulty,
                       tp.duration, tp.price, tp.pdf_path, tp.image_guid, tpp.status
                FROM training_plan_purchases tpp
                INNER JOIN training_plans tp ON tpp.plan_id = tp.id
                WHERE tpp.client_id = ?
            ");
    
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }
    
            // Escapar e insertar parámetro
            $escUserId = $this->realEscapeString($userId);
            $stmt->bind_param("i", $escUserId);
    
            // Ejecutar
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
    
            // Asignar los resultados
            $stmt->bind_result($id, $trainer_id, $name, $description, $difficulty,
                               $duration, $price, $pdf_path, $image_guid, $status);
    
            // Construir DTOs de los resultados
            while ($stmt->fetch()) {
                $plan = new PlanClientDTO($id, $trainer_id, $name, $description, $difficulty,
                                            $duration, $price, $image_guid, $pdf_path, $status);
                $plans[] = $plan;
            }
    
            // Cerrar la consulta
            $stmt->close();
    
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    
        return $plans;
    }


    public function deletePlan($planId)
    {
        try {
            // Obtener conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparar consulta para eliminar el plan de la tabla
            $stmt = $conn->prepare("DELETE FROM training_plans WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Enlazar parámetro (asegúrate de que realEscapeString está definido si lo usas)
            $planId = $this->realEscapeString($planId);
            $stmt->bind_param('i', $planId);

            // Ejecutar la eliminación
            if (!$stmt->execute()) {
                throw new \Exception("Error al eliminar el plan: " . $stmt->error);
            }

            // Verificar si se eliminó algún registro
            if ($stmt->affected_rows === 0) {
                throw new \Exception("No se encontró el plan con ID: " . $planId);
            }

            // Cerrar statement
            $stmt->close();

            return true; // Éxito

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    

    /**
     * Obtiene la lista básica de entrenadores (id y nombre)
     * 
     * @return array Lista de entrenadores [['id' => x, 'name' => 'y'], ...]
     */
    public function getTrainers()
    {
        $trainers = array();

        try {
            $conn = application::getInstance()->getConnectionDb();
            $query = "SELECT id FROM users WHERE usertype = 3 ORDER BY id ASC";

            $stmt = $conn->query($query);
            if (!$stmt) {
                throw new \Exception("Error al ejecutar la consulta: " . $conn->error);
            }

            while ($row = $stmt->fetch_assoc()) {
                $trainers[] = [
                    'id' => $row['id']
                ];
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $trainers;
    }

    public function registerPlan($planDTO) 
    {
        $plan_id = null;
        
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL para insertar el plan
            $stmt = $conn->prepare("
            INSERT INTO training_plans 
            (trainer_id, name, description, difficulty, duration, price, image_guid, pdf_path, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");         

            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Extraer y escapar valores del DTO
            $trainer_id = $this->realEscapeString($planDTO->getTrainerId());
            $name = $this->realEscapeString($planDTO->getName());
            $description = $this->realEscapeString($planDTO->getDescription());
            $difficulty = $this->realEscapeString($planDTO->getDifficulty());
            $duration = $this->realEscapeString($planDTO->getDuration());
            $price = $this->realEscapeString($planDTO->getPrice());
            $image_guid = $this->realEscapeString($planDTO->getImageGuid());
            $pdf_path = $this->realEscapeString($planDTO->getPdfPath());
            $created_at = $this->realEscapeString($planDTO->getCreatedAt());

            // Enlazamos los parámetros
            $stmt->bind_param("isssdssss", 
                $trainer_id,    // i (integer)
                $name,          // s (string)
                $description,   // s (string)
                $difficulty,    // s (string)
                $duration,      // d (double)
                $price,         // d (double)
                $image_guid,    // s (string)
                $pdf_path,      // s (string)
                $created_at    // s (string)
            );

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al registrar el plan: " . $stmt->error);
            }

            // Obtener ID del plan insertado
            $plan_id = $stmt->insert_id;

            // Cerrar statement
            $stmt->close();
        } catch (\Exception $e) {
            error_log("Error en registerPlan: " . $e->getMessage());
            throw $e;
        }

        return $plan_id;
    }

    /**
     * Construye la consulta SQL para buscar planes con filtros
     */
    private function buildSearchQuery($filters)
    {
        $query = "SELECT * FROM training_plans WHERE ";
        $args = array();
        $types = '';

        // Filtro por nombre
        if (!empty($filters['name'])) {
            $query .= "name LIKE ? AND ";
            $args[] = "%" . $this->realEscapeString($filters['name']) . "%";
            $types .= 's';
        }

        // Filtro por entrenador
        if (!empty($filters['trainer_id'])) {
            $query .= "trainer_id = ? AND ";
            $args[] = $this->realEscapeString($filters['trainer_id']);
            $types .= 'i';
        }

        // Filtro por dificultad
        if (!empty($filters['difficulty'])) {
            $query .= "difficulty = ? AND ";
            $args[] = $this->realEscapeString($filters['difficulty']);
            $types .= 's';
        }

        // Filtro por precio mínimo
        if (!empty($filters['minPrice'])) {
            $query .= "price >= ? AND ";
            $args[] = $this->realEscapeString($filters['minPrice']);
            $types .= 'd';
        }

        // Filtro por precio máximo
        if (!empty($filters['maxPrice'])) {
            $query .= "price <= ? AND ";
            $args[] = $this->realEscapeString($filters['maxPrice']);
            $types .= 'd';
        }

        // Filtro por duración mínima
        if (isset($filters['minDuration']) && $filters['minDuration'] !== '') {
            $query .= "duration >= ? AND ";
            $args[] = $this->realEscapeString($filters['minDuration']);
            $types .= 'i';
        }

        // Filtro por duración máxima
        if (isset($filters['maxDuration']) && $filters['maxDuration'] !== '') {
            $query .= "duration <= ? AND ";
            $args[] = $this->realEscapeString($filters['maxDuration']);
            $types .= 'i';
        }

        // Eliminar el último AND si hay filtros
        if (!empty($args)) {
            $query = substr($query, 0, -5);
        } else {
            // Si no hay filtros, quitar el WHERE
            $query = "SELECT * FROM training_plans";
        }

        return ['query' => $query, 'params' => $args, 'types' => $types];
    }


}