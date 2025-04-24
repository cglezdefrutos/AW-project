<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\application;
use TheBalance\plan\planAppService;
use TheBalance\plan\planDTO;
use TheBalance\catalog\planCatalogContent;
use TheBalance\catalog\planCatalogFilterForm;
use TheBalance\utils\utilsFactory;

$app = application::getInstance();

$titlePage = "Catálogo de Planes";
$mainContent = "";

// Crear el formulario de filtros
$form = new planCatalogFilterForm();
$htmlFilterForm = $form->Manage();

// Creamos el array de planDTOs
$plansDTO = array();

// Tomamos la instancia del servicio de planes
$planAppService = planAppService::GetSingleton();

// Si no hay filtros aplicados, mostrarlos todos
if (!isset($_GET["search"]) || $_GET["search"] != "true") {
    // Limpiamos resultados anteriores
    unset($_SESSION["foundedPlansJSON"]);

    // Tomar todos los planes activos de la BBDD
    $plansDTO = $planAppService->searchTrainingPlans(array("active" => 1));
}
// Si hay filtros aplicados y se ha utilizado la barra de búsqueda
else if(isset($_GET["name"]) && $_GET["name"] != "") {
    // Tomamos el contenido de la barra de busqueda
    $name = $_GET["name"];

    // Crear un array de filtros con el nombre del plan
    $filters = array();
    $filters['name'] = $name;

    $plansDTO = $planAppService->searchTrainingPlans($filters);
}
// Si hay filtros aplicados desde el formulario
else {
    $foundedPlansJSON = array();

    if (isset($_SESSION["foundedPlansJSON"])) {
        $foundedPlansJSON = json_decode($_SESSION["foundedPlansJSON"], true);
    } 

    // Convertir los datos decodificados en objetos planDTO
    $plansDTO = array_map(function($planData) {
        return new planDTO(
            $planData['id'],
            $planData['trainer_id'],
            $planData['name'],
            $planData['description'],
            $planData['difficulty'],
            $planData['duration'],
            $planData['price'],
            $planData['image_guid'],
            $planData['pdf_path'],
            $planData['created_at']
        );
    }, $foundedPlansJSON);
}

// Generar el contenido del catálogo
$catalog = new planCatalogContent($plansDTO);
$htmlCatalog = $catalog->generateContent();

// Si no hay planes, mostramos mensaje
if (empty($plansDTO)) {
    $htmlCatalog .= utilsFactory::createAlert("No se encontraron planes con los filtros seleccionados.", "warning");
}

// Combinar el formulario y el catálogo
$mainContent .= <<<EOS
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <h2>Filtros</h2>
                $htmlFilterForm
            </div>
            <div class="col-md-9">
                <h1 class="mb-4">Planes de Entrenamiento</h1>
                $htmlCatalog
            </div>
        </div>
    </div>
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';