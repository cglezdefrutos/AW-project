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

            // Limpiamos los datos para evitar inyecciones de código
            $id = htmlspecialchars($event->getId());
            $name = htmlspecialchars($event->getName());
            $desc = htmlspecialchars($event->getDesc());
            $date = htmlspecialchars($event->getDate());
            $location = htmlspecialchars($event->getLocation());
            $price = htmlspecialchars($event->getPrice());
            $capacity = htmlspecialchars($event->getCapacity());
            $categoryName = htmlspecialchars($event->getCategoryName());

            // Generar la fila de la tabla
            $html .= <<<EOS
                <tr>
                    <td>$name</td>
                    <td>$desc</td>
                    <td>$date</td>
                    <td>$location</td>
                    <td>$price</td>
                    <td>$capacity</td>
                    <td>$categoryName</td>
                    <td>
                        <button class="btn btn-primary edit-event" data-id="$id">Editar</button>
                        <button class="btn btn-danger delete-event mt-2" data-id="$id">Eliminar</button>
                    </td>
                </tr>
            EOS;
        }

        return $html;
    }
}