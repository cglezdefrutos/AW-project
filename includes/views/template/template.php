<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link rel="stylesheet" type="text/css" href="CSS/styles.css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/AW-project/CSS/styles.css">
        <link rel="icon" type="image/png" href="img/logo_thebalance.png">
        <title><?=$titlePage?></title>
    </head>
    <body>
        <div class="container-fluid"> 
            <header class="row align-items-center bg-primary text-white mb-0">
                <?php
                    require("includes/views/common/header.php");
                ?>
            </header>
            
            <div class="row">
                <?php
                    require("includes/views/common/menu.php");
                ?>
            </div>

            <div class="row align-items-center mb-4">
                <main class="col">
                    <?= $mainContent ?>
                </main>
            </div>

            <footer class="row bg-dark text-white p-0 mb-0">
                <?php
                    require("includes/views/common/footer.php");
                ?>
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>

