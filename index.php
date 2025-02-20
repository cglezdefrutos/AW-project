<?php
    session_start();

    $tituloPagina = 'Inicio - The Balance';

    $contenidoPrincipal=<<<EOS
        <h1>Página principal</h1>
        <p> Aquí está el contenido público, visible para todos los usuarios. </p>
    EOS;

    require("includes/views/template/template.php");
?>