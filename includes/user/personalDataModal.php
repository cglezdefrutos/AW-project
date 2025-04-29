<?php

namespace TheBalance\user;

class personalDataModal
{
    /**
     * Genera el HTML del modal para cambiar el email
     *
     * @return string HTML del modal
     */
    public static function generateChangeEmailModal(): string
    {
        return <<<EOS
            <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeEmailModalLabel">Cambiar Email</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="changeEmailForm">
                                <div class="mb-3">
                                    <label for="newEmail" class="form-label">Nuevo Email:</label>
                                    <input type="email" id="newEmail" name="newEmail" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        EOS;
    }

    /**
     * Genera el HTML del modal para cambiar la contraseña
     *
     * @return string HTML del modal
     */
    public static function generateChangePasswordModal(): string
    {
        return <<<EOS
            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Cambiar Contraseña</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="changePasswordForm">
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Nueva Contraseña:</label>
                                    <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="repeatNewPassword" class="form-label">Repite Nueva Contraseña:</label>
                                    <input type="password" id="repeatNewPassword" name="repeatNewPassword" class="form-control" required>
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