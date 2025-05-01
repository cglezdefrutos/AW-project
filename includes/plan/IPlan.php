<?php

namespace TheBalance\plan;

/**
 * Interfaz para planes de entrenamiento
 */
interface IPlan
{
    /**
     * Busca planes de entrenamiento aplicando filtros
     * 
     * @param array $filters Filtros de búsqueda (name, trainer_id, difficulty, minPrice, maxPrice)
     * @return array Lista de planDTO
     */
    public function searchTrainingPlans($filters);

    /**
     * Obtiene un plan específico por su ID
     * 
     * @param int $id ID del plan
     * @return planDTO Datos del plan
     */
    public function getPlanById($id);

    /**
     * Obtiene la lista de entrenadores disponibles
     * 
     * @return array Lista de trainerDTO
     */
    public function getTrainers();

    /**
     * Busca planes de entrenamiento aplicando filtros
     * 
     * @param array $TrainerId es el Id del entrenador del que queremos los planes
     * @return array Lista de planDTO
     */
    public function getPlansByTrainerId($TrainerId);

}