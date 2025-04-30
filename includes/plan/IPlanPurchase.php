<?php

namespace TheBalance\plan;

interface IPlanPurchase
{

    /**
     * Obtiene una compra de plan por su ID
     * 
     * @param planPurchaseDTO $purchaseDTO Datos de la compra del plan 
     * @return bool resultado de la operación
     */
    public function createPurchase(planPurchaseDTO $purchaseDTO);

    /**
     * Obtiene una compra de plan por su ID
     * 
     * @param int $id ID de la compra del plan
     * @return planPurchaseDTO Datos de la compra del plan
     */
    public function getPurchaseById($id);
}
