<?php

declare(strict_types=1);

namespace App\Servicios;

use App\Excepciones\CredencialesInvalidasException;
use App\Modelos\Cuenta;
use App\Repositorios\RepositorioCuenta;
use App\Repositorios\RepositorioUsuario;


final class ServicioAutenticacion
{
    public function __construct(
        private readonly RepositorioCuenta $repositorioCuenta,
        private readonly RepositorioUsuario $repositorioUsuario,
    ) {
    }

    
    public function autenticar(string $numeroCuenta, string $contrasena): Cuenta
    {
        $mensajeGenerico = 'Número de cuenta o contraseña incorrectos';

        $cuenta = $this->repositorioCuenta->buscarPorNumero($numeroCuenta);
        if ($cuenta === null) {
            throw new CredencialesInvalidasException($mensajeGenerico);
        }

        $usuario = $this->repositorioUsuario->buscarPorCuentaId($cuenta->id);
        if ($usuario === null) {
            throw new CredencialesInvalidasException($mensajeGenerico);
        }

        if (!password_verify($contrasena, $usuario->claveHash)) {
            throw new CredencialesInvalidasException($mensajeGenerico);
        }

        return $cuenta;
    }

    
    public function reconfirmarContrasena(int $cuentaId, string $contrasena): void
    {
        $usuario = $this->repositorioUsuario->buscarPorCuentaId($cuentaId);

        if ($usuario === null || !password_verify($contrasena, $usuario->claveHash)) {
            throw new CredencialesInvalidasException('La contraseña ingresada no es correcta');
        }
    }
}