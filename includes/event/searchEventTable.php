<?php

namespace TheBalance\event;

use TheBalance\views\common\baseTable;

class searchEventTable extends baseTable
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
            $html .= '<td>' . htmlspecialchars($event->getEmailProvider()) . '</td>';
            $html .= '<td><a href="joinEvent.php?id=' . htmlspecialchars($event->getId()) . '">Apuntarse</a></td>';
            $html .= '</tr>';
        }

        return $html;
    }
}