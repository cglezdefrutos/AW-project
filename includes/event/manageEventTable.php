<?php

namespace TheBalance\event;

use TheBalance\views\common\baseTable;

class manageEventTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        // Generar filas de la tabla
        foreach ($this->data as $event) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($event->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getDesc()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getDate()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getLocation()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getPrice()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getCapacity()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getCategoryName()) . '</td>';
            $html .= '<td>';
            $html .= '<a href="updateEvents.php?eventId=' . htmlspecialchars($event->getId()) . '">Editar</a>';
            $html .= ' o ';
            $html .= '<a href="manageEvents.php?eventId=' . htmlspecialchars($event->getId()) . '" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este evento?\');">Eliminar</a>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
}