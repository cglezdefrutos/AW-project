<?php

    include __DIR__ . "/../views/common/baseForm.php";
    include __DIR__ . "/eventAppService.php";
    
    class joinEventForm extends baseForm
    {
        private $eventId;
        private $userId;

        public function __construct($eventId, $userId)
        {
            parent:: __construct('joinEventForm');
            $this->eventId = $eventId;
            $this->userId = $userId;
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

            /* $html .= <<<EOF
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['email'] ?? '') . '">'; */

            $html .= <<<EOF
                    <label for="phone">Teléfono:</label>
                    <input type="tel" id="phone" name="phone" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['phone'] ?? '') . '">';

            $html .= <<<EOF
                    <input type="hidden" name="event_id" value="{$this->eventId}">
                    <input type="hidden" name="user_id" value="{$this->userId}">
                    <button type="submit" name="join_event">Apuntarse</button>
                </fieldset>
            EOF;

            return $html;
        }

        protected function Process($data)
        {
            // Array de errores
            $result = array();

            // Filtrado y sanitización de los datos
            $userId = trim($data['user_id'] ?? '');
            $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

            $eventId = trim($data['event_id'] ?? '');
            $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);  

            $username =  trim($data['name'] ?? '');
            $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $phone = trim($data['phone'] ?? '');
            $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);

            if(count($result) === 0)
            {
                try 
                {
                    $process_data = array(
                        'user_id' => $userId,
                        'event_id' => $eventId,
                        'user_name' => $username,
                        'user_phone' => $phone,
                    );
    
                    $eventAppService = eventAppService::GetSingleton();
                    $join = $eventAppService->joinEvent($process_data);
    
                    if($join === false)
                    {
                        $result[] = "Error al apuntarse al evento";
                    }
                    else
                    {
                        $result = "joinEvent.php?success=true";
                    }
                } 
                catch (userAlreadyJoinEventException $e) 
                {
                    $result[] = $e->getMessage();
                }
            } 

            return $result;
        }
    }
    
?>