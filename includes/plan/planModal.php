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
        <!-- Modal Plan -->
        <div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <form id="planForm" enctype="multipart/form-data">
                <div class="modal-header">
                <h5 class="modal-title" id="planModalLabel">Gestionar Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                <input type="hidden" name="idplan" id="idplan">
                <input type="hidden" name="currentImageGUID" id="currentImageGUID">
                <input type="hidden" name="planCreatedAt" id="planCreatedAt">

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del plan</label>
                    <input type="text" class="form-control" name="name" id="planName" maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" name="description" id="planDescription" rows="3" maxlength="1000" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="difficulty" class="form-label">Dificultad</label>
                    <select class="form-select" name="difficulty" id="planDifficulty" required>
                    <option value="">Selecciona dificultad</option>
                    <option value="Fácil">Fácil</option>
                    <option value="Intermedio">Intermedio</option>
                    <option value="Difícil">Difícil</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duración (en semanas)</label>
                    <input type="number" class="form-control" name="duration" id="planDuration" min="1" max="1000" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Precio (€)</label>
                    <input type="number" class="form-control" name="price" id="planPrice" step="0.01" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Imagen</label>
                    <input type="file" class="form-control" name="image" id="planImage" accept="image/*">
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>

            </div>
        </div>
        </div>
        EOS;
    }

}
