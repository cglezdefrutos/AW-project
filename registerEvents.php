<?php
    require_once("includes/config.php");
    require_once("includes/event/eventDTO.php");
    require_once("includes/event/registerEventForm.php");

    // Comprobar si se ha pulsado el botón de registrar otro evento y resetear la sesión antes de renderizar la página
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registerAnother"])) {
        $_SESSION["register"] = false;
        header("Location: " . $_SERVER["PHP_SELF"]); // Recargar la página para mostrar el formulario de nuevo
        exit();
    }

    $titlePage = "Registrar eventos";
    $mainContent = "";

    if (!isset($_SESSION["register"]) || $_SESSION["register"] === false) 
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
        $mainContent = <<<EOS
            <h1>Evento registrado correctamente.</h1>
            <form method="post">
                <button type="submit" name="registerAnother">Registrar otro evento</button>
                <a href="http://localhost/AW-project/index.php">
                    <button type="button">Volver a inicio</button>
                </a>
            </form>
        EOS;
    }

    require_once("includes/views/template/template.php");
?>
