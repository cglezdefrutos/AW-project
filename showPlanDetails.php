<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\plan\planAppService;
use TheBalance\plan\planDetailsContent;
use TheBalance\application;

$titlePage = "Detalles del Plan";
$mainContent = "";

// Obtenemos la ID del plan de la URL
$planId = $_GET['id'] ?? null;
if ($planId == null) {
    throw new \Exception("No se ha especificado el plan.");
}

// Obtenemos el plan correspondiente a la ID
$planAppService = planAppService::GetSingleton();
$planDTO = $planAppService->getPlanById($planId);
if ($planDTO == null) 
{
    throw new \Exception("No se ha encontrado el plan.");
}

// Generamos el contenido del plan
$planDetailsContent = new planDetailsContent($planDTO);
$mainContent .= $planDetailsContent->generateContent();

require_once BASE_PATH.'/includes/views/template/template.php';