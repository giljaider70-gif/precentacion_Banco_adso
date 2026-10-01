<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Excepciones\CredencialesInvalidasException;
use App\Nucleo\Conexion;
use App\Nucleo\ControladorBase;
use App\Repositorios\RepositorioCuenta;
use App\Repositorios\RepositorioUsuario;
use App\Servicios\ServicioAutenticacion;

final class LoginControlador extends ControladorBase
{
    public function mostrarAccion(): void
    {
        if (isset($_SESSION['cuenta_id'])) {
            $this->redirigir('panel/index');
        }

        $this->renderizar('login', [
            'tituloPagina' => 'Iniciar sesión',
            'error' => null,
        ]);
    }

    public function procesarAccion(): void
    {
        $numeroCuenta = trim((string) ($_POST['numero_cuenta'] ?? ''));
        $contrasena = (string) ($_POST['contrasena'] ?? '');

        $conexion = Conexion::obtenerInstancia();
        $servicio = new ServicioAutenticacion(
            new RepositorioCuenta($conexion),
            new RepositorioUsuario($conexion),
        );

        try {
            $cuenta = $servicio->autenticar($numeroCuenta, $contrasena);

            // Solo se guarda el id de la cuenta autenticada; la
            // contraseña nunca se guarda en la sesión.
            session_regenerate_id(true);
            $_SESSION['cuenta_id'] = $cuenta->id;

            $this->redirigir('panel/index');
        } catch (CredencialesInvalidasException $error) {
            $this->renderizar('login', [
                'tituloPagina' => 'Iniciar sesión',
                'error' => $error->getMessage(),
            ]);
        }
    }

    public function salirAccion(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirigir('login/mostrar');
    }
}
