<?php

declare(strict_types=1);

namespace App\Nucleo;


abstract class ControladorBase
{
    protected function requerirSesion(): void
    {
        if (!isset($_SESSION['cuenta_id'])) {
            $this->redirigir('login/mostrar');
        }
    }

    protected function idCuentaSesion(): int
    {
        return (int) $_SESSION['cuenta_id'];
    }

    protected function renderizar(string $vista, array $datos = []): void
    {
        Vista::render($vista, $datos);
    }

    protected function redirigir(string $ruta): never
    {
        header('Location: index.php?ruta=' . $ruta);
        exit;
    }


    protected function guardarFlash(string $clave, string $mensaje): void
    {
        $_SESSION[$clave] = $mensaje;
    }

    protected function leerFlash(string $clave): ?string
    {
        $mensaje = $_SESSION[$clave] ?? null;
        unset($_SESSION[$clave]);

        return $mensaje;
    }
}
