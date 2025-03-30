<?php

namespace TheBalance\product;

/**
 * Clase que contiene la lógica de la aplicación de productos
 */
class productAppService
{
    // Patrón Singleton
    /**
     * @var productAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see productAppService}.
     * 
     * @return productAppService Obtiene la única instancia de la <code>productAppService</code>
     */
    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    /**
     * Evita que se pueda instanciar la clase directamente.
     */
    private function __construct()
    {
        
    } 

    /**
     * Busca productos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function searchProducts($filters)
    {
        $IProduct = productFactory::CreateProduct();

        return $IProduct->searchProducts($filters);
    }

}
    

