<?php

namespace TheBalance\catalog;

use TheBalance\plan\planAppService;

/**
 * Clase para generar el contenido del catálogo de planes
 */
class planCatalogContent
{
    private $plans;

    public function __construct($plans)
    {
        $this->plans = $plans;
    }

    /**
     * Genera el contenido HTML del catálogo de planes
     * 
     * @return string Contenido HTML del catálogo
     */
    public function generateContent()
    {
        $html = '<div class="row">';

        foreach ($this->plans as $plan) 
        {
            // Obtener URL de la imagen del plan
            $imageUrl = planAppService::GetSingleton()->getPlanImagePath($plan->getImageGuid());
            
            // Formatear el precio
            $formattedPrice = number_format($plan->getPrice(), 2);
            
            // Mostrar dificultad con iconos
            $difficultyStars = $this->getDifficultyStars($plan->getDifficulty());

            $html .= <<<EOS
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="card h-100 plan-card">
                        <img src="{$imageUrl}" class="card-img-top catalog-img-custom" alt="{$plan->getName()}">
                        
                        <div class="card-body">
                            <h5 class="card-title">{$plan->getName()}</h5>
                            
                            <div class="mb-2">
                                <span class="badge bg-info">{$plan->getDuration()}</span>
                                <span class="ms-2">{$difficultyStars}</span>
                            </div>
                            
                            <p class="card-text text-truncate">{$plan->getDescription()}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-success fw-bold">{$formattedPrice} €</span>
                                <a href="planDetail.php?id={$plan->getId()}" class="btn btn-primary btn-sm">Ver Plan</a>
                            </div>
                        </div>
                    </div>
                </div>
            EOS;
        }

        $html .= '</div>'; // Cierre de row

        return $html;
    }

    /**
     * Convierte el nivel de dificultad en estrellas visuales
     * 
     * @param string $difficulty Nivel de dificultad
     * @return string HTML con estrellas
     */
    private function getDifficultyStars($difficulty)
    {        
        $levels = [
            'Principiante' => 1,
            'Intermedio' => 2,
            'Avanzado' => 3,
            'Experto' => 4
        ];
        
        $level = $levels[$difficulty] ?? 1;
        
        $stars = str_repeat('<i class="fas fa-star text-warning"></i>', $level);
        $stars .= str_repeat('<i class="far fa-star text-warning"></i>', 4 - $level);
        
        return $stars;
    }
}