<?php

namespace TheBalance\plan;

class planDetailsContent
{
    private $planDTO;

    public function __construct($planDTO)
    {
        $this->planDTO = $planDTO;
    }

    public function generateContent()
    {
        $html = '';
        
        // Reemplaza esto si tenés un servicio tipo planAppService
        $imageUrl = planAppService::GetSingleton()->getPlanImagePath($this->planDTO->getImageGuid());

        $html .= <<<EOF
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <img src="{$imageUrl}" class="img-fluid rounded" alt="{$this->planDTO->getName()}">
                </div>

                <div class="col-md-6 mt-5 px-5">
                    <h1 class="mb-3">{$this->planDTO->getName()}</h1>
                    <p class="text-muted">{$this->planDTO->getDescription()}</p>
                    <p><strong>Dificultad:</strong> {$this->planDTO->getDifficulty()}</p>
                    <p><strong>Duración (Días):</strong> {$this->planDTO->getDuration()}</p>
                    <h3 class="text-success mb-4">{$this->planDTO->getPrice()} €</h3>
EOF;

        // Generar el formulario de añadir al carrito
        $form = new planPaymentForm($this->planDTO);
        $htmlplanPaymentForm = $form->Manage();

        $html .= <<<EOF
                    $htmlplanPaymentForm
                </div>
            </div>
EOF;

        return $html;
    }
}
