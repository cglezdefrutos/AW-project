<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseTable;

class managePlanTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        // Generar filas de la tabla
        foreach ($this->data as $plan) {
            
            $imageUrl = planAppService::GetSingleton()->getPlanImagePath($plan->getImageGuid());

            $html .= '<tr>';

            $html .= '<td class="text-center align-middle">';
            $html .= '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($plan->getName()) . '" class="img-fluid table-image" style="max-height: 100px;">';
            $html .= '</td>';

            $html .= '<td>' . htmlspecialchars($plan->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getDescription()) . '</td>'; 
            $html .= '<td>' . htmlspecialchars($plan->getDifficulty()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getDuration()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getPrice()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getCreatedAt()) . '</td>';

            //Celda de acciones
            $html .= '<td>';
            $html .= '<div class="d-flex flex-column gap-2">';
            $html .= '<button class="btn btn-info managePlan" data-id="' . htmlspecialchars($plan->getId()) . '">Editar</button>';
            $html .= '<button class="btn btn-danger eliminarPlan" data-id="' . htmlspecialchars($plan->getId()) . '">Borrar</button>';
            $html .= '<button class="btn btn-warning view-plan-pdf" data-id="' . htmlspecialchars($plan->getId()) . '">Ver Detalles</button><br>';
            $html .= '</div>';
            $html .= '</td>';
        }

        return $html;
    }  
} 