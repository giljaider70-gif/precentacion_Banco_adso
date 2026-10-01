<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Nucleo\Conexion;
use App\Nucleo\ControladorBase;
use App\Repositorios\RepositorioCuenta;

final class PanelControlador extends ControladorBase
{
    
    public function indexAccion(): void
    {
        $this->requerirSesion();

        $repositorioCuenta = new RepositorioCuenta(Conexion::obtener());
        $idCuenta = $this->idCuentaSesion();

        $cuenta = $repositorioCuenta->buscarPorId($idCuenta);
        $saldo = $repositorioCuenta->obtenerSaldo($idCuenta);

        $this->renderizar('panel', [
            'tituloPagina' => 'Panel de la cuenta',
            'cuenta' => $cuenta,
            'saldo' => $saldo,
            'mensajeExito' => $this->leerFlash('flash_exito'),
        ]);
    }
}
