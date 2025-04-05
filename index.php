<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\utils\utilsFactory;

$titlePage = 'Inicio - The Balance';

$carousel = utilsFactory::createCarousel();

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
            <div class="card h-100">
                <img src="$imgPath/catalogo.jpg" class="card-img-top" alt="Tienda">
                <div class="card-body">
                    <h5 class="card-title">Tienda</h5>
                    <p class="card-text">Descubre los mejores suplementos y equipamiento deportivo para mejorar tu rendimiento.</p>
                    <a href="catalog.php" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="$imgPath/eventos.png" class="card-img-top" alt="Eventos">
                <div class="card-body">
                    <h5 class="card-title">Eventos</h5>
                    <p class="card-text">Descubre y participa en los mejores eventos organizados por nuestra comunidad.</p>
                    <a href="searchEvents.php" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="$imgPath/trainer.jpg" class="card-img-top" alt="Eventos">
                <div class="card-body">
                    <h5 class="card-title">Planes de entrenamiento</h5>
                    <p class="card-text">Accede a planes de entrenamiento personalizados para alcanzar tus objetivos.</p>
                    <a href="index.php" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 4 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="$imgPath/workwithus.jpg" class="card-img-top" alt="Trabaja">
                <div class="card-body">
                    <h5 class="card-title">Trabaja con nosotros</h5>
                    <p class="card-text">Descubre las oportunidades laborales que tenemos para ti.</p>
                    <a href="index.php" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>
    </div>
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';