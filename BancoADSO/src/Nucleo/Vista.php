<?php

declare(strict_types=1);

namespace App\Nucleo;


final class Vista
{
    public static function render(string $vista, array $datos = []): void
    {
        extract($datos, EXTR_SKIP);

        $rutaVista = dirname(__DIR__, 2) . '/vistas/' . $vista . '.php';

        ob_start();
        require $rutaVista;
        $contenido = ob_get_clean();

        require dirname(__DIR__, 2) . '/vistas/layout.php';
    }
}
