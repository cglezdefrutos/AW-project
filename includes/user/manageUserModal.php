<?php

namespace TheBalance\user;

class manageUserModal
{
    public static function generateEditUserModal(): string
    {
        return <<<EOS
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email:</label>
                                    <input type="email" id="editEmail" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editUserType" class="form-label">Tipo de Usuario:</label>
                                    <select id="editUserType" name="userType" class="form-control" required>
                                        <option value="0">Admin</option>
                                        <option value="1">Cliente</option>
                                        <option value="2">Proveedor</option>
                                        <option value="3">Entrenador</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        EOS;
    }
}