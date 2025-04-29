<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

$titlePage = "Checkout";
$mainContent = "";


require_once BASE_PATH.'/includes/views/template/template.php';