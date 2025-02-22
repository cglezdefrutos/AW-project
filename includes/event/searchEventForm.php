<?php
    include __DIR__ . "/../views/common/baseForm.php";
    include __DIR__ . "/eventAppService.php";

    class searchEventForm extends baseForm
    {
        public function __construct()
        {
            parent:: __construct('searchEventForm');
        }

        protected function CreateFields($initialData)
        {

            $html = <<<EOF
                <fieldset>
                    <legend>Buscar eventos</legend>

                    <label for="name">Nombre del evento:</label>
                    <input type="text" name="name" id="name" placeholder="Ej: Fitness" value="<?php echo htmlspecialchars($initialData['name'] ?? ''); ?>">

                    <label for="start_date">Desde:</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($initialData['start_date'] ?? ''); ?>">

                    <label for="end_date">Hasta:</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($initialData['end_date'] ?? ''); ?>">

                    <label for="min_price">Precio mínimo (€):</label>
                    <input type="number" name="min_price" id="min_price" step="0.01" placeholder="0" value="<?php echo htmlspecialchars($initialData['min_price'] ?? ''); ?>">

                    <label for="max_price">Precio máximo (€):</label>
                    <input type="number" name="max_price" id="max_price" step="0.01" placeholder="1000" value="<?php echo htmlspecialchars($initialData['max_price'] ?? ''); ?>">

                    <label for="location">Ubicación:</label>
                    <input type="text" name="location" id="location" placeholder="Ej: Madrid" value="<?php echo htmlspecialchars($initialData['location'] ?? ''); ?>">

                    <label for="category">Categoría:</label>
                    <select name="category" id="category">
                        <option value="">Todas</option>
                        <option value="Futbol" <?php echo ($initialData['category'] ?? '') == 'Futbol' ? 'selected' : ''; ?>>Futbol</option>
                        <option value="Baloncesto" <?php echo ($initialData['category'] ?? '') == 'Baloncesto' ? 'selected' : ''; ?>>Baloncesto</option>
                        <option value="Fitness" <?php echo ($initialData['category'] ?? '') == 'Fitness' ? 'selected' : ''; ?>>Fitness</option>
                        <option value="Conferencias" <?php echo ($initialData['category'] ?? '') == 'Conferencias' ? 'selected' : ''; ?>>Conferencias</option>
                    </select>

                    <input type="submit" value="Buscar">
                </fieldset>
            EOF;

            return $html;
        }

        protected function Process($data)
        {
            $result = array();

            // Filtrado y sanitización de los datos
            $eventName = trim($datos['name'] ?? '');
            $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $startDate = trim($datos['start_date'] ?? '');
            $endDate = trim($datos['end_date'] ?? '');
            $minPrice = trim($datos['min_price'] ?? '');
            $maxPrice = trim($datos['max_price'] ?? '');

            $location = trim($datos['location'] ?? '');
            $location = filter_var($location, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $category = trim($datos['category'] ?? '');

            if(count($result) === 0)
            {
                // Creamos el DTO del evento

                // Llamamos a la instancia de SA de eventos para que busque los eventos

                // Manejamos el control de errores en función de lo que nos devuelva el SA

            }

            return $result;
        }
    }
?>