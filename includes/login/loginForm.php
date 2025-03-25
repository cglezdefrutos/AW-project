<?php

namespace TheBalance\login;

use TheBalance\views\common\baseForm;
use TheBalance\user\userAppService;
use TheBalance\user\userDTO;
use TheBalance\application;

/**
 * Formulario de inicio de sesión
 */
class loginForm extends baseForm
{
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct('loginForm');
    }
    
    /**
     * Crea los campos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return string HTML
     */
    protected function CreateFields($data)
    {
        $email = '';
        
        if ($data) 
        {
            $email = isset($data['email']) ? $data['email'] : $email;
        }

        $html = <<<EOF
            <fieldset>
                <legend>Usuario y contraseña</legend>
                <p><label>Email:</label> <input type="email" name="email" value="$email"/></p>
                <p><label>Password:</label> <input type="password" name="password" /></p>
                <button type="submit" name="login">Entrar</button>
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
        $result = array();

        $email = trim($data['email'] ?? '');
        
        $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
        if ( empty($email) ) 
        {
            $result[] = "El email de usuario no puede estar vacío";
        }
        
        $password = trim($data['password'] ?? '');
        
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ( empty($password) ) 
        {
            $result[] = "El password no puede estar vacío.";
        }
        
        if (count($result) === 0) 
        {
            $userDTO = new userDTO(0, $email, $password, -1);

            $userAppService = userAppService::GetSingleton();

            $foundedUserDTO = $userAppService->login($userDTO);

            if ( ! $foundedUserDTO ) 
            {
                // No se da pistas a un posible atacante
                $result[] = "El usuario o el password no coinciden";
            } 
            else 
            {
                $userJSON = json_encode($foundedUserDTO);
                
                $app = application::getInstance();
                $app->loginUser($userJSON);

                $result = 'index.php';
            }
        }
        return $result;
    }
}