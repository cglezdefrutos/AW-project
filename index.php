<?php

require_once __DIR__.'/includes/config.php';

$titlePage = 'Inicio - The Balance';

$mainContent=<<<EOS

    <section class="welcome">
        <h1>Bienvenido a The Balance</h1>
        <h2>¿Quienes somos?</h2>
        <p>
            The Balance es una plataforma web que revoluciona el mundo del fitness al combinar e-commerce de productos deportivos con servicios 
            personalizados para los entusiastas del deporte. Los usuarios pueden comprar suplementos y equipamiento, inscribirse en eventos deportivos y 
            acceder a planes de entrenamiento diseñados por nuestros nutricionistas. La plataforma proporciona un enfoque integral de la salud y el bienestar, 
            permitiendo que los usuarios mejoren su rendimiento físico con el respaldo de expertos. Además, los eventos deportivos patrocinados crean un 
            ecosistema donde las marcas y los clientes interactúan y comparten experiencias.
        </p>
        <img src="/AW-project/img/logo_thebalance.png" alt="Logo de The Balance">
    </section>

    <section class="features">
        <h2>Descubre todo lo que puedes hacer</h2>
        <div class="feature-box">
            <h3>Tienda</h3>
            <p>Descubre los mejores suplementos y equipamiento deportivo para mejorar tu rendimiento.</p>
        </div>
        <div class="feature-box">
            <h3>Eventos</h3>
            <p>Encuentra los mejores eventos deportivos cerca de ti y apúntate a ellos.</p>
        </div>
        <div class="feature-box">
            <h3>Planes de entrenamiento</h3>
            <p>Accede a planes de entrenamiento personalizados para alcanzar tus objetivos.</p>
        </div>
        <div class="feature-box">
            <h3>Trabaja con nosotros</h3>
            <p>Descubre las oportunidades laborales que tenemos para ti.</p>
        </div>
    </section>
EOS;

require_once __DIR__.'/includes/views/template/template.php';