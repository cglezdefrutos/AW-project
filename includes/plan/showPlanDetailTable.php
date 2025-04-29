<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseTable;
use TheBalance\plan\planAppService;

class showPlanDetailTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';
        $total = 0;

        foreach ($this->data as $plan) {
            $total += $plan->getPrice();

            $imageUrl = planAppService::GetSingleton()->getPlanImagePath($plan->getImageGuid());
            $pdfPath = htmlspecialchars($plan->getPdfPath());

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($plan->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getDifficulty()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getDuration()) . '</td>';
            $html .= '<td>' . htmlspecialchars($plan->getCreatedAt()) . '</td>';
            $html .= '<td class="align-middle text-center"><img src="' . htmlspecialchars($imageUrl) . '" class="img-fluid table-image" style="max-width: 100px;"></td>';
            $html .= '<td class="text-center">';
            $html .= '<a href="'. $pdfPath .'" class="btn btn-primary" download>Descargar PDF</a>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="6">' . htmlspecialchars($plan->getDescription()) . '</td>';
            $html .= '<tr>';
        }

        // Fila con el precio del plan
        $html .= '<tr class="table-active">';
        $html .= '<td colspan="5" class="text-end"><strong>Precio:</strong></td>';
        $html .= '<td class="text-center"><strong>' . number_format($total, 2) . ' €</strong></td>';
        $html .= '</tr>';

        return $html;
    }
}
