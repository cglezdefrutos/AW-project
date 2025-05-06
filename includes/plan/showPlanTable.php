<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseTable;

class showPlanTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        // Generar filas de la tabla
        foreach ($this->data as $plan) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($plan->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getDescription()) . '</td>'; 
            $html .= '<td>' . htmlspecialchars($plan->getDifficulty()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getDuration()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getStatus()) . '</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-info view-plan-pdf mb-1" data-id="' . htmlspecialchars($plan->getId()) . '">Ver Detalles</button><br>';
            $html .= '<button class="btn btn-primary edit-statusPlan mt-1" data-id="' . htmlspecialchars($plan->getIdPurchase()) . '">Cambiar Estado</button>';
            $html .= '</td>';

            $html .= '</tr>';
        }

        return $html;
    }   
}
