<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="icon" type="image/png" href="img/logo_thebalance.png">
    <title><?=$titlePage?></title>
</head>
<body>
    <div id="container"> 
        <?php
            require("includes/views/common/header.php");
        ?>

        <main>
            <article>
                <?=$mainContent?>
            </article>
        </main>

        <?php
            require("includes/views/common/footer.php");
        ?>
    </div>
</body>
</html>
