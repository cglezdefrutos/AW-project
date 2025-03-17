<?php

namespace TheBalance\views\common;

use TheBalance\application;

/**
 * Clase base de los objetos de acceso a datos
 */
abstract class baseDAO 
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }     

    /**
     * Protege de inyecciones SQL escapando los caracteres especiales de $field
     * 
     * @param string $field Campo a escapar
     * 
     * @return string Campo escapado
     */
    protected function realEscapeString($field)
    {
        $conn = application::getInstance()->getConnectionDb();

        return $conn->real_escape_string($field);
    }

    /**
     * Ejecuta una consulta SQL estática (no depende de los parámetros del usuario) y devuelve un array con los resultados (la tabla)
     * 
     * @param string $sql Consulta SQL
     * 
     * @return array|bool Tabla de resultados|false
     */
    protected function ExecuteQuery($sql)
    {
        if($sql != "")
        {
            $conn = application::getInstance()->getConnectionDb();

            $rs = $conn->query($sql);

            $dataTable = array();
            
            while ($row = $rs->fetch_assoc())
            {  
                array_push($dataTable, $row);
            }
                
            return $dataTable;
        } 
        else
        {
            return false;
        }
    }

    /**
     * Ejecuta un comando SQL (INSERT, UPDATE o DELETE) estático (no depende de los parámetros del usuario) y devuelve el objeto de conexión
     * 
     * @param string $sql Comando SQL
     * 
     * @return mysqli|bool Objeto de conexión|false
     */
    protected function ExecuteCommand($sql)
    {
        if($sql != "")
        {
            $conn = application::getInstance()->getConnectionDb();

            if ($conn->query($sql))
            {
                return $conn;
            }
        }

        return false;
    }
}