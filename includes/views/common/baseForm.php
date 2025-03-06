<?php
    abstract class baseForm
    {
        private $formId;    // Identificador único para el formulario (identificarlo cuando se envíe y gestionar sus acciones)
        private $action;    // Define la acción del formulario (es decir, el destino de donde se enviarán los datos)

        public function __construct($formId, $options = array() )
        {
            $this->formId = $formId;
            $defaultOptions = array( 'action' => null, );

            $options = array_merge($defaultOptions, $options);

            $this->action = $options['action'];

            if(!$this->action)
            {
                $this->action = htmlentities($_SERVER['PHP_SELF']);
            }
        }

        public function Manage()
        {
            // Si no se ha enviado el formulario -> Se crea el formulario
            if ( ! $this->IsSent($_POST) ) 
            {
                return $this->Create();
            } 
            // Si se ha enviado el formulario -> Lo procesamos
            else 
            {
                // Pasamos los datos (variable $POST) al método abstracto Process(), que será implementado por cada subclase
                $result = $this->Process($_POST);
                
                // Si process devuelve un array -> Significa que hay errores
                if ( is_array($result) ) 
                {
                    // Llamamos al método Create() con el array de errores y las variables enviadas ($POST)
                    return $this->Create($result, $_POST);
                } 
                // Si no devuelve un array -> Se ha devuelto la URL a donde hay que redirigir al usuario
                else 
                {
                    header('Location: ' . $result);                   
                    exit();
                }
            }  
        }

        private function IsSent(&$params)
        {
            error_log("IsSent() ejecutado. Contenido de params: " . print_r($params, true));

            if (!isset($params['action'])) {
                error_log("ERROR: No se encontro 'action' en los datos enviados.");
            } elseif ($params['action'] !== $this->formId) {
                error_log("ERROR: El valor de 'action' no coincide. Esperado: " . $this->formId . ", Recibido: " . $params['action']);
            }
            
            // Verifica si el formulario ha sido enviado
            return isset($params['action']) && $params['action'] == $this->formId;
        }

        private function Create($errors = array(), &$data = array())
        {
            // En caso de haber errores -> Genera los mensajes de errores
            $html= $this->CreateErrors($errors);

            // Se genera el formulario
            $html .= '<form method="POST" action="'.$this->action.'" id="'.$this->formId.'" >';
            $html .= '<input type="hidden" name="action" value="'.$this->formId.'" />';
            
            // Llama al método abstracto para crear los campos, que será implementado por cada subclase 
            $html .= $this->CreateFields($data);
            $html .= '</form>';
            
            // Se retorna todo el código HTML
            return $html;
        }

        private function CreateErrors($errors)
        {
            $html='';
            $numErrors = count($errors);
            if (  $numErrors == 1 ) 
            {
                $html .= "<ul><li>".$errors[0]."</li></ul>";
            } 
            else if ( $numErrors > 1 ) 
            {
                $html .= "<ul><li>";
                $html .= implode("</li><li>", $errors);
                $html .= "</li></ul>";
            }
            return $html;
        }

        // Métodos abstractos que las clases hijas deben implementar
        abstract protected function CreateFields($initialData);
        abstract protected function Process($data);
    }
?>