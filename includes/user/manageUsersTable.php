<?php

namespace TheBalance\user;

use TheBalance\views\common\baseTable;

class manageUsersTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        foreach ($this->data as $user) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($user->id()) . '</td>';
            $html .= '<td>' . htmlspecialchars($user->email()) . '</td>';
            $html .= '<td>' . htmlspecialchars($user->usertypeText()) . '</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-primary edit-user me-2" data-id="' . htmlspecialchars($user->id()) . '" data-email="' . htmlspecialchars($user->email()) . '" data-usertype="' . htmlspecialchars($user->usertype()) . '">Editar</button>';
            $html .= '<button class="btn btn-danger delete-user" data-id="' . htmlspecialchars($user->id()) . '">Eliminar</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }
    
        return $html;
    }
}