<?php

namespace TheBalance\event;

use TheBalance\views\common\baseForm;

/**
 * Formulario de apuntarse a un evento
 */
class joinEventForm extends baseForm
{

    /**
     * Identificador del evento
     * 
     * @var int
     */
    private $eventId;
    
    /**
     * Identificador del usuario
     * 
     * @var int
     */
    private $userId;

    /**
     * Constructor
     * 
     * @param int $eventId Identificador del evento
     * @param int $userId Identificador del usuario
     */
    public function __construct($eventId, $userId)
    {
        parent:: __construct('joinEventForm');
        $this->eventId = $eventId;
        $this->userId = $userId;
    }

    /**
     * Crea los campos del formulario
     * 
     * @param array $initialData Datos iniciales del formulario
     * 
     * @return string Campos del formulario
     */
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

    /**
     * Procesa los datos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return array|string Errores de procesamiento|Redirección
     */
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
        if (empty($username) || strlen($username) > 50) {
            $result[] = 'El nombre es obligatorio y no debe exceder los 50 caracteres.';
        }

        $phone = trim($data['phone'] ?? '');
        $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        if (empty($phone) || strlen($phone) > 9) {
            $result[] = 'El teléfono es obligatorio y no debe exceder los 9 caracteres.';
        }

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