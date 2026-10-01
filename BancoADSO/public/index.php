<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 1. Cargamos el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Importamos la clase Router del núcleo
use App\Nucleo\Router;

// 3. Iniciamos la sesión (necesaria para $_SESSION['cuenta_id'])
$rutaSesiones = __DIR__ . '/../storage/sessions';
if (!is_dir($rutaSesiones)) {
    mkdir($rutaSesiones, 0777, true);
}
session_save_path($rutaSesiones);
session_start();

// 4. Inicializamos y despachamos el enrutador
$router = new Router();
$router->despachar();