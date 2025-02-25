<?php
    require_once("includes/config.php");

    require_once("includes/event/searchEventForm.php");

    $titlePage = "Buscar eventos";

    $form = new searchEventForm();

    $htmlSearchEventForm = $form->Manage();

    $mainContent = <<<EOS
        <h1>Eventos disponibles</h1>
        $htmlSearchEventForm
    EOS;

    require("includes/views/template/template.php");
?>