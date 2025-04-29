<?php

namespace TheBalance\event;

use TheBalance\views\common\baseTable;

class myEventsTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        foreach ($this->data as $event) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($event->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getDesc()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getCategoryName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getPrice()) . ' €</td>';
            $html .= '<td>' . htmlspecialchars($event->getLocation()) . '</td>';
            $html .= '<td>' . htmlspecialchars($event->getDate()) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
}