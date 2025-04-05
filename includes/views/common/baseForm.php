<?php

namespace TheBalance\views\common;

/**
 * Clase base para la creación de formularios
 */
abstract class baseForm
{
    /**
     * Identificador único para el formulario (identificarlo cuando se envíe y gestionar sus acciones)
     * 
     * @var string
     */
    private $formId;

    /**
     * URL a la que se enviarán los datos del formulario
     * 
     * @var string
     */
    private $action;

    /**
     * Tipo de codificación del formulario
     * 
     * @var string
     */
    private $enctype;

    /**
     * Constructor
     * 
     * @param string $formId Identificador único para el formulario
     * @param array $options Opciones del formulario
     */
    public function __construct($formId, $options = array() )
    {
        $this->formId = $formId;
        $defaultOptions = array( 
            'action' => null, 
            'enctype' => 'application/x-www-form-urlencoded',
        );

        $options = array_merge($defaultOptions, $options);

        $this->action = $options['action'];
        $this->enctype = $options['enctype'];

        if(!$this->action)
        {
            $this->action = htmlentities($_SERVER['PHP_SELF']);
        }
    }

    /**
     * Gestiona el formulario
     * 
     * @return string Código HTML del formulario
     */
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

    /**
     * Comprueba si el formulario ha sido enviado
     * 
     * @param array $params Parámetros del formulario
     * 
     * @return bool True si el formulario ha sido enviado, false en caso contrario
     */
    private function IsSent(&$params)
    {            
        // Verifica si el formulario ha sido enviado
        return isset($params['action']) && $params['action'] == $this->formId;
    }

    /**
     * Crea el formulario
     * 
     * @param array $errors Errores del formulario
     * @param array $data Datos del formulario
     * 
     * @return string Código HTML del formulario
     */
    private function Create($errors = array(), &$data = array())
    {
        // En caso de haber errores -> Genera los mensajes de errores
        $html= $this->CreateErrors($errors);

        // Se genera el formulario
        $html .= '<form method="POST" action="' . $this->action . '" enctype="' . $this->enctype . '" id="' . $this->formId . '" class="needs-validation" novalidate>';
        $html .= '<input type="hidden" name="action" value="'.$this->formId.'" />';
        
        // Llama al método abstracto para crear los campos, que será implementado por cada subclase 
        $html .= '<div class="row g-2">'; // Añade un contenedor de filas con espacio entre campos
        $html .= $this->CreateFields($data);
        $html .= '</div>';

        $html .= '</form>';
        
        // Se retorna todo el código HTML
        return $html;
    }

    /**
     * Genera los mensajes de errores
     * 
     * @param array $errors Errores del formulario
     * 
     * @return string Código HTML de los errores
     */
    private function CreateErrors($errors)
    {
        $html = count($errors) > 0 ? utilsFactory::createFormErrorsAlert($errors) : '';
        return $html;
    }

    /**
     * Crea los campos del formulario
     * 
     * @param array $initialData Datos iniciales del formulario
     * 
     * @return string Código HTML de los campos del formulario
     */
    abstract protected function CreateFields($initialData);
    
    /**
     * Procesa los datos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return array|string Array con los errores o string con la URL a la que redirigir
     */
    abstract protected function Process($data);
}