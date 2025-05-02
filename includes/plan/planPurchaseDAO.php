<?php

namespace TheBalance\plan;

use TheBalance\application;
use TheBalance\views\common\baseDAO;

/**
 * DAO para gestionar compras de planes de entrenamiento
 */
class planPurchaseDAO extends baseDAO implements IPlanPurchase
{
    /**
     * Registra una compra de plan
     * 
     * @param planPurchaseDTO $purchaseDTO
     * @return int ID de la compra insertada
     */
    public function createPurchase(planPurchaseDTO $purchaseDTO)
    {
        $purchaseId = null;

        try {
            $conn = application::getInstance()->getConnectionDb();
            $stmt = $conn->prepare("
                INSERT INTO training_plan_purchases (plan_id, client_id, purchase_date, status)
                VALUES (?, ?, ?, ?)
            ");

            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            $plan_id = $purchaseDTO->getPlanId();
            $client_id = $purchaseDTO->getClientId();
            $purchase_date = $purchaseDTO->getPurchaseDate();
            $status = $purchaseDTO->getStatus();

            $stmt->bind_param("iiss", $plan_id, $client_id, $purchase_date, $status);

            if (!$stmt->execute()) {
                throw new \Exception("Error al insertar la compra: " . $stmt->error);
            }

            $purchaseId = $stmt->insert_id;
            $stmt->close();
        } catch (\Exception $e) {
            error_log("Error en createPurchase: " . $e->getMessage());
            throw $e;
        }

        return $purchaseId;
    }

    /**
     * Obtiene una compra de plan por ID
     * 
     * @param int $id
     * @return planPurchaseDTO|null
     */
    public function getPurchaseById($id)
    {
        $purchase = null;

        try {
            $conn = application::getInstance()->getConnectionDb();
            $stmt = $conn->prepare("
                SELECT id, plan_id, client_id, purchase_date, status 
                FROM training_plan_purchases 
                WHERE id = ?
            ");

            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $stmt->bind_result($id, $plan_id, $client_id, $purchase_date, $status);

            if ($stmt->fetch()) {
                $purchase = new planPurchaseDTO($id, $plan_id, $client_id, $purchase_date, $status);
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log("Error en getPurchaseById: " . $e->getMessage());
            throw $e;
        }

        return $purchase;
    }

    /**
     * Obtiene una compra de plan por ID del plan y ID del cliente
     * 
     * @param int $planId
     * @param int $clientId
     * @return planPurchaseDTO|null
     */
    public function getPurchaseByPlanAndClient($planId, $clientId)
    {
        $purchase = null;

        try {
            $conn = application::getInstance()->getConnectionDb();
            $stmt = $conn->prepare("
                SELECT id, plan_id, client_id, purchase_date, status 
                FROM training_plan_purchases 
                WHERE plan_id = ? AND client_id = ?
                LIMIT 1
            ");

            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            $stmt->bind_param("ii", $planId, $clientId);

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $stmt->bind_result($id, $plan_id, $client_id, $purchase_date, $status);

            if ($stmt->fetch()) {
                $purchase = new planPurchaseDTO($id, $plan_id, $client_id, $purchase_date, $status);
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log("Error en getPurchaseByPlanAndClient: " . $e->getMessage());
            throw $e;
        }

        return $purchase;
    }
}
