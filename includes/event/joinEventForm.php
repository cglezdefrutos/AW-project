<?php

    include __DIR__ . "/../views/common/baseForm.php";
    include __DIR__ . "/eventAppService.php";

    class joinEventForm extends baseForm
    {
        public function __construct()
        {
            parent:: __construct('searchEventForm');
        }

        protected function CreateFields($initialData)
        {
            // Creamos el formulario de apuntarse al evento
            $html = <<<EOF
                <fieldset>
                    <legend>Apuntate al evento</legend>

                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required>
            EOF;

            $html .= htmlspecialchars($initialData['name'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required>
            EOF;
            
            $html .= htmlspecialchars($initialData['email'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="phone">Teléfono:</label>
                    <input type="tel" id="phone" name="phone" required>
            EOF;
            
            $html .= htmlspecialchars($initialData['phone'] ?? '') . '">';

            $html .= <<<EOF
                    <input type="hidden" name="event_id" value="{$this->eventId}">
                    <input type="submit" name="join_event" value="Apuntarse">
                </form>
            EOF;
        }

        protected function Process($data)
        {
        }
    }
    
?>