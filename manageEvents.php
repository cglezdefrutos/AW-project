<?php
    require_once("includes/config.php");
    require_once("includes/event/eventDTO.php");
    require_once("includes/event/eventAppService.php");

    $titlePage = "Gestionar eventos";
    $mainContent = "";

    if(!isset($_SESSION["user"]))
    {
        $mainContent = <<<EOS
            <h1>No es posible gestionar eventos si no has iniciado sesión.</h1>
        EOS;
    }
    else
    {
        $userDTO = json_decode($_SESSION["user"], true);
        $user_type = htmlspecialchars($userDTO["usertype"]);

        // Comprobar si el usuario es proveedor o administrador
        if ( $user_type != 2 &&  $user_type != 0)
        { 
            $mainContent = <<<EOS
            <h1>No es posible gestionar eventos si no se es proveedor.</h1>
        EOS;
        } 
        else 
        {
            // Obtenemos la instancia del servicio de eventos
            $eventAppService = eventAppService::GetSingleton();

            // Manejar la eliminación del evento si se proporciona un eventId en la URL
            if (isset($_GET['eventId'])) {
                $eventId = $_GET['eventId'];
                $eventAppService->deleteEvent($eventId);
                $mainContent .= "<p>Evento eliminado correctamente.</p>";
            }

            // Cogemos los eventos correspondientes al usuario
            $eventsDTO = $eventAppService->getEventsByUserType($user_type);

            $mainContent = <<<EOS
                <h1>Gestión de eventos</h1>
                <table>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Precio</th>
                        <th>Capacidad</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
            EOS;

            foreach($eventsDTO as $event)
            {
                $mainContent .= <<<EOS
                    <tr>
                        <td>{$event->getName()}</td>
                        <td>{$event->getDesc()}</td>
                        <td>{$event->getDate()}</td>
                        <td>{$event->getLocation()}</td>
                        <td>{$event->getPrice()}</td>
                        <td>{$event->getCapacity()}</td>
                        <td>{$event->getCategory()}</td>
                        <td>
                            <a href="updateEvents.php?eventId={$event->getId()}">Editar</a>
                        </td>
                        <td>
                             <a href="manageEvents.php?eventId={$event->getId()}" onclick="return confirm('¿Estás seguro de que deseas eliminar este evento?');">Eliminar</a>
                        </td>
                    </tr>
                EOS;

                $mainContent .= "</table>";
            }
        }
    }

    require_once("includes/views/template/template.php");
?>