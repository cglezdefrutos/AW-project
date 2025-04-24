<?php

namespace TheBalance\catalog;

use TheBalance\plan\planAppService;

/**
 * Clase para generar el contenido del catálogo.
 */
class catalogContent
{
    private $plan;

    public function __construct($plan)
    {
        $this->plan = $plan;
    }

    /**
     * Genera el contenido HTML del catálogo.
     * 
     * @return string Contenido HTML del catálogo.
     */
    public function generateContent()
    {
        $html = '<div class="container mt-4">';
        $html .= '<h2 class="text-center mb-4">Catálogo de Planes de entrenamiento</h2>';
        $html .= '<div class="row">';

        foreach ($this->plan as $plan) 
        {
            // Construir la URL de la imagen usando el GUID
            $imageUrl = planAppService::GetSingleton()->getPlanImagePath($plan->getImageGuid());

            $html .= <<<EOS
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{$imageUrl}" class="card-img-top catalog-img-custom" alt="{$plan->getName()}">
                        
                        <div class="card-body text-center">
                            <!-- Nombre del plan -->
                            <h5 class="card-title">{$plan->getName()}</h5>
                            
                            <p class="card-text text-success"><strong>{$plan->getPrice()} €</strong></p>
                            
                            <a href="planDetails.php?id={$plan->getId()}" class="btn btn-primary">Ver Detalles</a>
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