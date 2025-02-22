<?php
    session_start();

    include("includes/event/searchEventForm.php");

    $titlePage = "Buscar eventos";

    $form = new searchEventForm();

    $htmlSearchEventForm = $form->Manage();

    $mainContent = <<<EOS
        <h1>Eventos disponibles</h1>
        $htmlSearchEventForm
    EOS;

    require("includes/views/template/template.php");
?>