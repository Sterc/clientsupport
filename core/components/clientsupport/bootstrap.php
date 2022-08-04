<?php
/** @var MODX\Revolution\modX $modx */
require_once __DIR__ . '/vendor/autoload.php';

$modx->services->add('clientsupport', function () use ($modx) {
    return new Sterc\ClientSupport\ClientSupport($modx);
});
