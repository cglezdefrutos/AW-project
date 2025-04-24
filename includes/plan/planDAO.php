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
                $created_at,
                $is_active
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
                $created_at,
                $is_active
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
     * Obtiene la lista básica de entrenadores (id y nombre)
     * 
     * @return array Lista de entrenadores [['id' => x, 'name' => 'y'], ...]
     */
    public function getTrainers()
    {
        $trainers = array();

        try {
            $conn = application::getInstance()->getConnectionDb();
            $query = "SELECT id, name FROM users WHERE role = 'trainer' ORDER BY name ASC";

            $stmt = $conn->query($query);
            if (!$stmt) {
                throw new \Exception("Error al ejecutar la consulta: " . $conn->error);
            }

            while ($row = $stmt->fetch_assoc()) {
                $trainers[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $trainers;
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