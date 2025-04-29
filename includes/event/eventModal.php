<?php

namespace TheBalance\event;

/**
 * Clase para generar el modal de edición de eventos
 */
class eventModal
{
    /**
     * Genera el HTML del modal
     *
     * @return string HTML del modal
     */
    public static function generateEditModal(): string
    {        
        return <<<EOS
            <!-- Modal para editar producto -->
            <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEventModalLabel">Editar Evento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editEventForm" method="POST">
                                <input type="hidden" name="eventId" id="eventId">
                                
                                <div class="mb-3">
                                    <label for="eventName" class="form-label">Nombre del Evento</label>
                                    <input type="text" class="form-control" id="eventName" name="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="eventDescription" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="eventDescription" name="description" rows="3" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="eventDate" class="form-label">Fecha</label>
                                    <input type="datetime-local" class="form-control" id="eventDate" name="date" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="eventLocation" class="form-label">Lugar</label>
                                    <input type="text" class="form-control" id="eventLocation" name="location" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="eventPrice" class="form-label">Precio</label>
                                    <input type="number" class="form-control" id="eventPrice" name="price" step="0.01" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="eventCapacity" class="form-label">Capacidad</label>
                                    <input type="number" class="form-control" id="eventCapacity" name="capacity" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="eventCategory" class="form-label">Categoría</label>
                                    <div id="categoryField"></div> <!-- Este div será dinámico -->
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        EOS;
    }
}