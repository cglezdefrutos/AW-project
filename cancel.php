<?php

include_once __DIR__ . '/includes/config.php';

$titlePage = "Pago Cancelado";
$mainContent = "";

$mainContent .= <<<EOS
    <div class="container cancel-page mt-5 d-flex justify-content-center">
        <div class="card shadow-lg cancel-card">
            <div class="card-header bg-danger text-white text-center">
                <h3 class="mb-0">Pago Cancelado</h3>
            </div>
            <div class="card-body text-center">
                <img src="/AW-project/img/logo_thebalance.png" alt="Pago cancelado" class="img-fluid mb-4 cancel-logo" style="max-width: 150px;">
                <p class="lead">El proceso de pago ha sido cancelado.</p>
                <p>Si deseas completar tu compra, puedes volver al carrito o explorar más productos en nuestro catálogo.</p>
                <hr>
                <div class="d-flex justify-content-center gap-3">
                    <a href="cart.php" class="btn btn-warning btn-lg">Volver al carrito</a>
                    <a href="catalog.php" class="btn btn-primary btn-lg">Ir al catálogo</a>
                </div>
            </div>
        </div>
    </div>
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';