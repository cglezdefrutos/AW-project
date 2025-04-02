<?php

namespace TheBalance\views\common;

abstract class baseTable
{
    protected $data;
    protected $columns;

    public function __construct(array $data, array $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    // Método abstracto para generar el contenido de la tabla
    abstract protected function generateTableContent();

    // Método para generar la tabla completa
    public function generateTable()
    {
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-bordered">';
        $html .= '<thead class="table-primary">';
        $html .= '<tr>';

        // Generar encabezados de la tabla
        foreach ($this->columns as $column) {
            $html .= '<th>' . htmlspecialchars($column) . '</th>';
        }

        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Generar contenido de la tabla
        $html .= $this->generateTableContent();

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
}