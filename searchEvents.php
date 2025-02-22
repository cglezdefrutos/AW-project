<?php
    session_start();

    $titlePage = "Buscar eventos"

    $mainContent = <<<EOS
        <h1>Eventos disponibles</h1>
        <p>Aqui estará el contenido de la página de eventos, con su formulario para filtrar<p>
    EOS;

    require("includes/views/template/template.php");
?>