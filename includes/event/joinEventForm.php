<?php

    include __DIR__ . "/../views/common/baseForm.php";
    include __DIR__ . "/eventAppService.php";

    class joinEventForm extends baseForm
    {
        private $eventId;

        public function __construct($eventId)
        {
            parent:: __construct('searchEventForm');
            $this->eventId = $eventId;
        }

        protected function CreateFields($initialData)
        {
            // Creamos el formulario de apuntarse al evento
            $html = <<<EOF
                <fieldset>
                    <legend>Apuntate al evento</legend>

                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required value="
            EOF;

            $html .= htmlspecialchars($initialData['name'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['email'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="phone">Teléfono:</label>
                    <input type="tel" id="phone" name="phone" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['phone'] ?? '') . '">';

            $html .= <<<EOF
                    <input type="hidden" name="event_id" value="{$this->eventId}">
                    <input type="submit" name="join_event" value="Apuntarse">
                </form>
            EOF;

            return $html;
        }

        protected function Process($data)
        {
            // Array de errores
            $result = array();

            // Filtrado y sanitización de los datos
            $eventName =  trim($datos['name'] ?? '');
            $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $email = trim($datos['email'] ?? '');
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            $phone = trim($datos['phone'] ?? '');
            $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);

            $eventId = trim($datos['event_id'] ?? '');
            $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);  

            if(count($result) === 0)
            {
                $data = array(
                    'user_id' => $_SESSION['user_id'],
                    'username' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'event_id' => $eventId
                );

                $eventAppService = eventAppService::GetSingleton();
                $join = $eventAppService->joinEvent($data);

                if($join)
                {
                    $_SESSION['sentJoinEvent'] = true;
                    $result = "joinEvent.php";
                }
                else
                {
                    $result[] = "Error al apuntarse al evento";
                }
            }

            return $result;
        }
    }
    
?>