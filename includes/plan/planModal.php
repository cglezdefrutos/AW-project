<?php

namespace TheBalance\plan;

class planModal
{
    /**
     * Genera el HTML del modal
     *
     * @return string HTML del modal
     */
    public static function generateEditModal(): string
    {
        
        return <<<EOS
        <div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPlanModalLabel">Editar Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form id="editPlanForm" enctype="multipart/form-data">

                            <!-- Campo oculto para el ID del plan -->
                            <input type="hidden" id="planId" name="planId">

                            <!-- Nombre del plan -->
                            <div class="mb-3">
                                <label for="planName" class="form-label">Nombre del Plan:</label>
                                <input type="text" id="planName" name="name" class="form-control" required>
                            </div>

                            <!-- Descripción del plan -->
                            <div class="mb-3">
                                <label for="planDescription" class="form-label">Descripción:</label>
                                <textarea id="planDescription" name="description" class="form-control" rows="3" required></textarea>
                            </div>

                            <!-- Dificultad del plan -->
                            <div class="mb-3">
                                <label for="planDifficulty" class="form-label">Dificultad:</label>
                                <select id="planDifficulty" name="difficulty" class="form-control" required>
                                    <option value="Principiante">Principiante</option>
                                    <option value="Intermedio">Intermedio</option>
                                    <option value="Avanzado">Avanzado</option>
                                    <option value="Experto">Experto</option>
                                </select>
                            </div>

                            <!-- Duración del plan -->
                            <div class="mb-3">
                                <label for="planDuration" class="form-label">Duración (días):</label>
                                <input type="number" id="planDuration" name="duration" class="form-control" required>
                            </div>

                            <!-- Precio del plan -->
                            <div class="mb-3">
                                <label for="planPrice" class="form-label">Precio (€):</label>
                                <input type="number" id="planPrice" name="price" class="form-control" step="0.01" required>
                            </div>

                            <!-- Imagen -->
                            <div class="mb-3">
                                <label for="planImage" class="form-label">Imagen del plan:</label>
                                <input type="file" name="image" id="planImage" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la imagen.</small>
                                <div class="mt-2">
                                    <img id="currentPlanImage" src="" alt="Imagen actual" style="max-height: 200px;">
                                </div>
                                <!-- Campo oculto para el GUID de la imagen actual -->
                                <input type="hidden" name="currentImageGUID" id="currentImageGUID" value="">
                            </div>

                            <!-- PDF del plan -->
                            <div class="mb-3">
                                <label for="planPdf" class="form-label">Archivo PDF del plan:</label>
                                <input type="file" name="pdf" id="planPdf" class="form-control" accept="application/pdf">
                                <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar el PDF.</small>
                                <div class="mt-2" id="currentPdfContainer" style="display: none;">
                                    <p class="mb-0">Archivo actual: <span id="currentPdfName"></span></p>
                                </div>
                                <!-- Campo oculto para el GUID del PDF actual -->
                                <input type="hidden" name="currentPdfGUID" id="currentPdfGUID" value="">
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
     * Genera el HTML del modal para cambiar el estado de un plan
     *
     * @return string HTML del modal
     */
    public static function generateChangeStatusModal(): string
    {
        return <<<EOS
        <!-- Modal Cambiar Estado -->
        <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeStatusModalLabel">Cambiar Estado del Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="changeStatusForm">
                            <!-- Campo oculto para el ID del plan -->
                            <input type="hidden" id="statusPlanId" name="planId">

                            <!-- Opciones de estado -->
                            <div class="mb-3">
                                <label class="form-label">Selecciona el nuevo estado:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusCompleted" value="Completado" required>
                                    <label class="form-check-label" for="statusCompleted">Completado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusPaused" value="Pausado" required>
                                    <label class="form-check-label" for="statusPaused">Pausado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusInProgress" value="Activo" required>
                                    <label class="form-check-label" for="statusInProgress">Activo</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Guardar Estado</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        EOS;
    }    

}
