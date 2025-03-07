<?php
    require_once("includes/config.php");
    require_once("includes/event/eventDTO.php");
    require_once("includes/event/searchEventForm.php");

    $titlePage = "Buscar eventos";
    $mainContent = "";

    if (!isset($_GET["search"]) || $_GET["search"] != "true") 
    {
        // Limpiamos resultados anteriores
        unset($_SESSION["foundedEventsDTO"]);

        $form = new searchEventForm();

        $htmlSearchEventForm = $form->Manage();
    
        $mainContent = <<<EOS
            <h1>Eventos disponibles</h1>
            $htmlSearchEventForm
        EOS;
    } 
    else 
    {
        // Verificar el contenido de $_SESSION["foundedEventsDTO"]
        if (!isset($_SESSION["foundedEventsDTO"])) {
            echo "No se encontraron eventos.";
            exit();
        }

        // Decodificar el JSON almacenado en la sesión
        $foundedEventsDTO = json_decode($_SESSION["foundedEventsDTO"], true);

        // Verificar que la decodificación fue exitosa y que es un array
        if (!is_array($foundedEventsDTO)) {
            echo "Error al decodificar los datos de eventos.";
            exit();
        } 

        $html = '<div class="table-container">';

        // Mostramos cada uno de eventDTO encontrados por pantalla en una tabla
        $html .= '<table>';
        $html .= '<tr><th>Nombre</th><th>Descripción</th><th>Fecha</th><th>Ubicación</th><th>Precio</th><th>Capacidad</th><th>Categoría</th><th></th></tr>';

        foreach($foundedEventsDTO as $eventDTO)
        {
            // Verificar que $eventDTO es un array con los campos esperados
            if (!is_array($eventDTO) || !isset($eventDTO['name'], $eventDTO['desc'], $eventDTO['date'], $eventDTO['location'], $eventDTO['price'], $eventDTO['category'], $eventDTO['capacity'])) {
                echo "Error: El elemento no tiene la estructura esperada.";
                var_dump($eventDTO);
                exit();
            }

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($eventDTO['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($eventDTO['desc']) . '</td>';
            $html .= '<td>' . htmlspecialchars($eventDTO['date']) . '</td>';
            $html .= '<td>' . htmlspecialchars($eventDTO['location']) . '</td>';
            $html .= '<td>' . htmlspecialchars($eventDTO['price']) . '</td>';
            $html .= '<td>' . htmlspecialchars($eventDTO['capacity']) . '</td>';
            $html .= '<td>' . htmlspecialchars($eventDTO['category']) . '</td>';
            $html .= '<td><a href="joinEvent.php?id=' . htmlspecialchars($eventDTO['id']) . '">Apuntarse</a></td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';

        $mainContent = <<<EOS
            <h1>Eventos disponibles</h1>
            $html
        EOS;
    }

    require_once("includes/views/template/template.php");
?>