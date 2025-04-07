<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\utils\utilsFactory;

$titlePage = 'Inicio - The Balance';

$carousel = utilsFactory::createCarousel();
$card1 = utilsFactory::createCard(IMG_PATH . "/catalogo.jpg", 'Tienda', 'Tienda', 'Descubre los mejores suplementos y equipamiento deportivo para mejorar tu rendimiento.', 'catalog.php');
$card2 = utilsFactory::createCard(IMG_PATH . "/eventos.png", 'Eventos', 'Eventos', 'Descubre y participa en los mejores eventos organizados por nuestra comunidad.', 'searchEvents.php');
$card3 = utilsFactory::createCard(IMG_PATH . "/trainer.jpg", 'Planes de entrenamiento', 'Planes de entrenamiento', 'Accede a planes de entrenamiento personalizados para alcanzar tus objetivos.', 'index.php');    
$card4 = utilsFactory::createCard(IMG_PATH . "/workwithus.jpg", 'Trabaja con nosotros', 'Trabaja con nosotros', 'Descubre las oportunidades laborales que tenemos para ti. Únete a nuestro equipo.', 'index.php');

$imgPath = IMG_PATH;

$mainContent=<<<EOS

    <div class="row">
        $carousel
    </div>

    <div class="row p-4">
        <div class="col-6 text-start mb-4">
            <h1 class="display-4">Bienvenido a The Balance</h1>
            <p class="lead">
                The Balance es una plataforma web que revoluciona el mundo del fitness al combinar e-commerce de productos deportivos con servicios 
                personalizados para los entusiastas del deporte. Los usuarios pueden comprar suplementos y equipamiento, inscribirse en eventos deportivos y 
                acceder a planes de entrenamiento diseñados por nuestros nutricionistas. La plataforma proporciona un enfoque integral de la salud y el bienestar, 
                permitiendo que los usuarios mejoren su rendimiento físico con el respaldo de expertos. Además, los eventos deportivos patrocinados crean un 
                ecosistema donde las marcas y los clientes interactúan y comparten experiencias.
            </p>
        </div>
        <div class="col-6 d-flex justify-content-center align-items-center mb-4">
            <img src="$imgPath/logo_thebalance.png" alt="Logo de The Balance">
        </div>
    </div>

    <div class="row p-4">
        <div class="col-12 text-center">
            <h2>Descubre nuestros servicios</h2>
        </div>
    </div>

    <div class="row p-3">
        <!-- Tarjeta 1 -->
        <div class="col-md-3 mb-4">
            $card1
        </div>

        <!-- Tarjeta 2 -->
        <div class="col-md-3 mb-4">
            $card2
        </div>

        <!-- Tarjeta 3 -->
        <div class="col-md-3 mb-4">
            $card3
        </div>

        <!-- Tarjeta 4 -->
        <div class="col-md-3 mb-4">
            $card4
        </div>
    </div>
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';