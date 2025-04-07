<?php

namespace TheBalance\utils;

use TheBalance\svg\iconSVG;

class utilsFactory
{
    /**
     * Crea un alert de Bootstrap con el mensaje pasado como parámetro
     * 
     * @param string $message Mensaje a mostrar
     * @param string $alertType Tipo de alert (success, danger, warning, info, etc.)
     * 
     * @return string HTML del alert
     */
    public static function createAlert($message, $alertType = 'success')
    {
        // Importar el icono SVG correspondiente al tipo de alerta
        $importedIcons = iconSVG::importIcons();
        $icon = iconSVG::getIcon($alertType);
        $type = 'alert-' . $alertType;
        
        $alert = <<<EOS
            $importedIcons
            <div class="alert $type alert-dismissible fade show" role="alert">
                $icon
                $message
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        EOS;
        return $alert;
    }

    /**
     * Crea un carrusel de Bootstrap con las imágenes de la pagina principal
     * 
     * @return string HTML del carrusel
     */
    public static function createCarousel()
    {
        $carousel = <<<EOS
            <div id="carousel" class="carousel slide" data-ride="carousel">
                <!-- Indicadores -->
                <ul class="carousel-indicators">
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </ul>
                <!-- Diapositivas -->
                <div class="carousel-inner">
                    <!-- Primera diapositiva -->
                    <div class="carousel-item active">
                        <img src="/AW-project/img/bellingham_carrusel.jpeg" class="img-fluid" alt="Promoción 1">
                        <div class="carousel-caption">
                            <h5 class="carousel-title"><a href="catalog.php?name=bellingham&search=true">Adidas X Jude Bellingham</a></h5>
                            <p class="carousel-text">Descubre la nueva colección de Adidas en colaboracion con Jude Bellingham.</p>
                        </div>
                    </div>
                    <!-- Segunda diapositiva -->
                    <div class="carousel-item">
                        <img src="/AW-project/img/nike_carrusel.jpg" class="img-fluid" alt="Promoción 2">
                        <div class="carousel-caption">
                            <h5 class="carousel-title"><a href="catalog.php?name=cleveland&search=true">LeBron James en Cleveland</a></h5>
                            <p class="carousel-text">Recuerda los mejores momentos de LeBron James en Cleveland con su camiseta retro.</p>
                        </div>
                    </div>
                    <!-- Tercera diapositiva -->
                    <div class="carousel-item">
                        <img src="/AW-project/img/spain_carrusel.jpg" class="img-fluid" alt="Promoción 3">
                        <div class="carousel-caption">
                            <h5 class="carousel-title"><a href="catalog.php?name=espa&search=true">Camiseta de la Selección Española</a></h5>
                            <p class="carousel-text">Apoya a la Selección Española con la nueva camiseta de la Eurocopa 2024.</p>
                        </div>
                    </div>
                </div>
                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        EOS;

        return $carousel;
    }

    public static function createCard($imagePath, $altText, $title, $description, $link, $buttonText = 'Ver más')
    {
        $html = <<<EOS
            <div class="card h-100">
                <img src="$imagePath" class="card-img-top card-img-custom" alt="$altText">
                <div class="card-body">
                    <h5 class="card-title">$title</h5>
                    <p class="card-text">$description</p>
                    <a href="$link" class="btn btn-primary">$buttonText</a>
                </div>
            </div>
        EOS;
        return $html;
    }

    /**
     * Crea un alert de Bootstrap con los errores de un formulario
     * 
     * @param array $errors Errores a mostrar
     * 
     * @return string HTML del alert
     */
    public static function createFormErrorsAlert($errors = array())
    {
        $message = '';
        
        $numErrors = count($errors);
        
        if ($numErrors == 1) 
        {
            $message .= "<ul><li>".$errors[0]."</li></ul>";
        } 
        else if ( $numErrors > 1 ) 
        {
            $message .= "<ul><li>";
            $message .= implode("</li><li>", $errors);
            $message .= "</li></ul>";
        }

        $alert = <<<EOS
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                $message
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        EOS;

        return $alert;
    }

    /**
     * Crea un contenido de error para mostrar en la página del Exception Handler
     * 
     * @param string $message Mensaje de error a mostrar
     * 
     * @return string HTML del contenido de error
     */
    public static function generateErrorContent($message) 
    {
        // Importar el icono SVG correspondiente al tipo de alerta
        $importedIcons = iconSVG::importIcons();
        $icon = iconSVG::getIcon('warning');

        $errorDetails = getenv('APP_ENV') === 'development' ? "<div class='alert alert-danger mt-4' role='alert'><strong>Detalles del error:</strong> $message</div>" : '';
        
        $content = <<<EOS
            $importedIcons
            <div class="container mt-0">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <!-- Ícono de error -->
                        $icon
                        <!-- Encabezado -->
                        <h1 class="display-4 text-danger">¡Oops! Algo salió mal</h1>
                        <!-- Mensaje descriptivo -->
                        <p class="lead">Parece que ha ocurrido un error inesperado. Estamos trabajando para solucionarlo.</p>
                        <!-- Detalles del error -->
                        $errorDetails
                        <!-- Botón para regresar -->
                        <a href="index.php" class="btn btn-primary btn-lg mt-3">
                            Volver a la página principal
                        </a>
                    </div>
                </div>
            </div>
        EOS;

        return $content;
    }
}