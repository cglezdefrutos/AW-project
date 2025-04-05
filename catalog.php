<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\application;
use TheBalance\product\productAppService;
use TheBalance\product\productDTO;
use TheBalance\catalog\catalogContent;
use TheBalance\catalog\catalogFilterForm;
use TheBalance\utils\utilsFactory;

$app = application::getInstance();

$titlePage = "Catálogo";
$mainContent = "";

// Crear el formulario de filtros
$form = new catalogFilterForm();
$htmlFilterForm = $form->Manage();

// Creamos el array de productDTOs
$productsDTO = array();

// Tomamos la instancia del servicio de productos
$productAppService = productAppService::GetSingleton();

// Si no hay filtros aplicados, mostrarlos (es decir, que en la url no haya un search=true)
if (!isset($_GET["search"]) || $_GET["search"] != "true") 
{
    // Limpiamos resultados anteriores
    unset($_SESSION["foundedProductsJSON"]);

    // Tomar todos los productos de la BBDD
    $productsDTO = $productAppService->searchProducts(array());
}
// Si hay filtros aplicados y se ha utilizado la barra de búsqueda, buscamos ese nombre
else if(isset($_GET["name"]) && $_GET["name"] != "") 
{
    // Tomamos el contenido de la barra de busqueda
    $name = $_GET["name"];

    // Crear un array de filtros con el nombre del producto
    $filters = array();
    $filters['name'] = $name;

    // Llamamos a la instancia de SA de productos
    $productAppService = productAppService::GetSingleton();
    $productsDTO = $productAppService->searchProducts($filters);
}
// Si hay filtros aplicados y no se ha utilizado la barra de búsqueda, buscamos por los filtros
else 
{
    $foundedProductsJSON = array();

    // Si $_SESSION["foundedProductsJSON"] esta definida, decodificamos el JSON almacenado en la sesión
    if (isset($_SESSION["foundedProductsJSON"])) 
    {
        $foundedProductsJSON = json_decode($_SESSION["foundedProductsJSON"], true);
    } 

    // Convertir los datos decodificados en objetos eventDTO
    $productsDTO = array_map(function($productData) {
        return new productDTO(
            $productData['id'],
            $productData['provider_email'],
            $productData['name'],
            $productData['description'],
            $productData['price'],
            $productData['category_DTO'],
            $productData['image_guid'],
            $productData['created_at'],
            $productData['sizes_DTO'],
            $productData['active'],
        );
    }, $foundedProductsJSON);
}

// Generar el contenido del catálogo
$catalog = new catalogContent($productsDTO);
$htmlCatalog = $catalog->generateContent();

// Si esta vacio productsDTO, mostramos un mensaje de alerta
if (empty($productsDTO)) 
{
    $htmlCatalog .= utilsFactory::createAlert("Lo sentimos, no hemos encontrado ningún producto que coincida con los filtros seleccionados.", "warning");
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
                $htmlCatalog
            </div>
        </div>
    </div>
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';