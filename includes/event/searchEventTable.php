<?php

namespace TheBalance\event;

use TheBalance\views\common\baseTable;

class searchEventTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';
        $eventAppService = eventAppService::getSingleton();

        // Generar filas de la tabla
        foreach ($this->data as $event) {
            $currentAssistants = $eventAppService->getCurrentAssistants($event->getId()); // Obtener asistentes actuales
            $availableSpots = $event->getCapacity() - $currentAssistants; // Calcular cupo disponible

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($event->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getDesc()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getDate()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getLocation()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getPrice()) . '</td>';
            $html .= '<td>' . htmlspecialchars($currentAssistants) . '/' . htmlspecialchars($event->getCapacity()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getCategoryName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getEmailProvider()) . '</td>';
            
            // Botón de "Apuntarse"
            if ($availableSpots > 0) {
                $html .= '<td><a href="joinEvent.php?id=' . htmlspecialchars($event->getId()) . '" class="btn btn-primary">Apuntarse</a></td>';
            } else {
                $html .= '<td><button class="btn btn-secondary" disabled>Cupo lleno</button></td>';
            }

            $html .= '</tr>';
        }

        return $html;
    }
}