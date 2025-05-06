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
     * @return IPlan DAO de planes creado
     */
    public static function CreateTrainingPlan() : IPlan
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

    /**
     * Crea un DAO para compras de planes
     * 
     * @return IPlanPurchase DAO de compras creado
     */
    public static function CreatePlanPurchase(): IPlanPurchase
    {

        $planPurchaseDAO = false;
        $config = "DAO"; // Puedes cambiar esto por configuración si lo necesitas

        if ($config === "DAO") {
            $planPurchaseDAO = new planPurchaseDAO(); // Implementación real con base de datos
        } else {
            // $planDAO = new planPurchaseMock(); // Opcional: Implementación mock para testing
            throw new \Exception("Implementación mock no disponible");
        }

        return $planPurchaseDAO;
    }
}