<?php
    require_once("includes/config.php");
    require_once("includes/event/eventDTO.php");
    require_once("includes/event/registerEventForm.php");

    $titlePage = "Regisrar eventos";
    $mainContent = "";

    if (!isset($_SESSION["register"]))
    {
        $form = new registerEventForm();

        $htmlRegisterEventForm = $form->Manage();
    
        $mainContent = <<<EOS
            <h1>Registre un evento</h1>
            $htmlRegisterEventForm
        EOS;
    } 
    else 
    {
        echo "Evento registrado correctamente.";
    }    

    require_once("includes/views/template/template.php");
?><?php
// TODO
?>