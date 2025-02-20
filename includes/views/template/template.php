<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../estilo.css" />
    <title><?=$titlePage?></title>
</head>
<body>
    <div id="container"> 
        <?php
            require("includes/views/common/header.php");
        ?>

        <main>
            <article>
                <?=$contenidoPrincipal?>
            </article>
        </main>

        <?php
            require("includes/views/common/footer.php");
        ?>
    </div>
</body>
</html>