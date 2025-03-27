<?php

namespace TheBalance\catalog;

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

        foreach ($this->products as $product) {
            $html .= <<<EOS
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{$product->getImageUrl()}" class="card-img-top" alt="{$product->getName()}">
                        <div class="card-body">
                            <h5 class="card-title">{$product->getName()}</h5>
                            <p class="card-text"><strong>Descripción:</strong> {$product->getDescription()}</p>
                            <p class="card-text"><strong>Categoría:</strong> {$product->getCategoryId()}</p>
                            <p class="card-text"><strong>Precio:</strong> {$product->getPrice()} €</p>
                            <p class="card-text"><strong>Stock:</strong> {$product->getStock()}</p>
                            <p class="card-text"><strong>Fecha de creación:</strong> {$product->getCreatedAt()}</p>
                            <a href="productDetails.php?id={$product->getId()}" class="btn btn-primary btn-block">Ver Detalles</a>
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