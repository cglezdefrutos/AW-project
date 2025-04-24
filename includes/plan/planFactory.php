<?php

namespace TheBalance\plan;

/**
 * Factory para planes de entrenamiento
 */
class planFactory
{
    /**
     * Crea un DAO de planes de entrenamiento
     * 
     * @return IPlanDAO DAO de planes creado
     */
    public static function CreateTrainingPlan() : IPlanDAO
    {
        $planDAO = false;
        $config = "DAO"; // Puedes cambiar esto por configuración si lo necesitas

        if ($config === "DAO") {
            $planDAO = new planDAO(); // Implementación real con base de datos
        } else {
            // $planDAO = new planMock(); // Opcional: Implementación mock para testing
            throw new \Exception("Implementación mock no disponible");
        }

        return $planDAO;
    }
}