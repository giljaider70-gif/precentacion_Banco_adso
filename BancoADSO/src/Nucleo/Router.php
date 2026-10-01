<?php

declare(strict_types=1);

namespace App\Nucleo;

final class Router
{
    private const RUTA_POR_DEFECTO = 'login/mostrar';

    public function despachar(): void
    {
        $ruta = (string) ($_GET['ruta'] ?? self::RUTA_POR_DEFECTO);
        $segmentos = array_pad(explode('/', $ruta, 2), 2, 'index');

        [$controlador, $accion] = $segmentos;

        $claseControlador = 'App\\Controladores\\' . ucfirst($controlador) . 'Controlador';
        $metodoAccion = $accion . 'Accion';

        if (!class_exists($claseControlador) || !method_exists($claseControlador, $metodoAccion)) {
            http_response_code(404);
            Vista::render('404', ['tituloPagina' => 'Página no encontrada']);

            return;
        }

        /** @var object $instancia */
        $instancia = new $claseControlador();
        $instancia->$metodoAccion();
    }
}
