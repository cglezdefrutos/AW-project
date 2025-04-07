<?php

namespace TheBalance\catalog;

use TheBalance\product\productAppService;

/**
 * Clase para generar el contenido del catálogo.
 */
class catalogContent
{
    private $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Genera el contenido HTML del catálogo.
     * 
     * @return string Contenido HTML del catálogo.
     */
    public function generateContent()
    {
        $html = '<div class="container mt-4">';
        $html .= '<h2 class="text-center mb-4">Catálogo de Productos</h2>';
        $html .= '<div class="row">';

        foreach ($this->products as $product) 
        {
            // Construir la URL de la imagen usando el GUID
            $imageUrl = productAppService::GetSingleton()->getProductImagePath($product->getImageGuid());

            $html .= <<<EOS
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{$imageUrl}" class="card-img-top" alt="{$product->getName()}">
                        
                        <div class="card-body text-center">
                            <!-- Nombre del producto -->
                            <h5 class="card-title">{$product->getName()}</h5>
                            
                            <p class="card-text text-success"><strong>{$product->getPrice()} €</strong></p>
                            
                            <a href="productDetails.php?id={$product->getId()}" class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            EOS;
        }

        $html .= '</div>'; // Cierre de row
        $html .= '</div>'; // Cierre de container

        return $html;
    }
}