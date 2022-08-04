<?php
/**
 * ClientSupport Connector
 *
 * @package clientsupport
 */
require_once dirname(__DIR__, 3) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

if (!$modx->services->has('clientsupport')) {
    return '';
}

$clientsupport = $modx->services->get('clientsupport');

/* handle request */
$modx->request->handleRequest([
    'processors_path'   => $clientsupport->getOption('processorsPath'),
    'location'          => '',
]);
