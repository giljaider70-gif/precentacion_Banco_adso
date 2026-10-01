<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Excepciones\CredencialesInvalidasException;
use App\Excepciones\SaldoInsuficienteException;
use App\Excepciones\ValorInvalidoException;
use App\Nucleo\Conexion;
use App\Nucleo\ControladorBase;
use App\Repositorios\RepositorioCuenta;
use App\Repositorios\RepositorioRetiro;
use App\Repositorios\RepositorioTransferencia;
use App\Repositorios\RepositorioUsuario;
use App\Servicios\ServicioAutenticacion;
use App\Servicios\ServicioCuenta;

final class RetiroControlador extends ControladorBase
{
    public function formularioAccion(): void
    {
        $this->requerirSesion();

        $this->renderizar('retiro', [
            'tituloPagina' => 'Realizar retiro',
            'error' => $this->leerFlash('flash_error'),
        ]);
    }

    
    public function procesarAccion(): void
    {
        $this->requerirSesion();

        $valor = trim((string) ($_POST['valor'] ?? ''));
        $contrasena = (string) ($_POST['contrasena'] ?? '');

        try {
            $this->servicioCuenta()->registrarRetiro($this->idCuentaSesion(), $valor, $contrasena);
            $this->guardarFlash('flash_exito', 'Retiro realizado correctamente. Tu saldo ya está actualizado.');
            $this->redirigir('panel/index');
        } catch (CredencialesInvalidasException|SaldoInsuficienteException|ValorInvalidoException $error) {
            $this->guardarFlash('flash_error', $error->getMessage());
            $this->redirigir('retiro/formulario');
        }
    }

    public function historialAccion(): void
    {
        $this->requerirSesion();

        $resumen = $this->servicioCuenta()->historialRetiros($this->idCuentaSesion());

        $this->renderizar('historialRetiros', [
            'tituloPagina' => 'Historial de retiros',
            'resumen' => $resumen,
        ]);
    }

    private function servicioCuenta(): ServicioCuenta
    {
        $conexion = Conexion::obtener();

        return new ServicioCuenta(
            $conexion,
            new RepositorioCuenta($conexion),
            new RepositorioRetiro($conexion),
            new RepositorioTransferencia($conexion),
            new ServicioAutenticacion(new RepositorioCuenta($conexion), new RepositorioUsuario($conexion)),
        );
    }
}
