<?php

declare(strict_types=1);

namespace App\Servicios;

use App\Excepciones\CuentaNoEncontradaException;
use App\Excepciones\SaldoInsuficienteException;
use App\Excepciones\ValorInvalidoException;
use App\Repositorios\RepositorioCuenta;
use App\Repositorios\RepositorioRetiro;
use App\Repositorios\RepositorioTransferencia;
use PDO;
use Throwable;


final class ServicioCuenta
{
    public function __construct(
        private readonly PDO $conexion,
        private readonly RepositorioCuenta $repositorioCuenta,
        private readonly RepositorioRetiro $repositorioRetiro,
        private readonly RepositorioTransferencia $repositorioTransferencia,
        private readonly ServicioAutenticacion $servicioAutenticacion,
    ) {
    }

    public function obtenerSaldo(int $cuentaId): string
    {
        return $this->repositorioCuenta->obtenerSaldo($cuentaId);
    }

    
    public function registrarRetiro(int $cuentaId, string $valorTexto, string $contrasena): void
    {
        $this->servicioAutenticacion->reconfirmarContrasena($cuentaId, $contrasena);
        $this->validarValorPositivo($valorTexto);

        $saldoActual = $this->repositorioCuenta->obtenerSaldo($cuentaId);
        if ((float) $saldoActual < (float) $valorTexto) {
            throw new SaldoInsuficienteException('El saldo disponible no alcanza para este retiro');
        }

        $this->conexion->beginTransaction();
        try {
            $this->repositorioCuenta->descontarSaldo($cuentaId, $valorTexto);
            $this->repositorioRetiro->registrar($cuentaId, $valorTexto);
            $this->conexion->commit();
        } catch (Throwable $error) {
            $this->conexion->rollBack();
            throw $error;
        }
    }

    /**
     * RF6 / RN6 / RN7: valida en el servidor, en orden, antes de tocar
     * la base de datos: contraseña reconfirmada, cuenta destino
     * existente, valor numérico y mayor que 0, saldo suficiente en
     * origen, y cuenta destino distinta de la cuenta origen. Si todas
     * pasan, débito, crédito e inserción del registro se ejecutan como
     * una única transacción atómica.
     */
    public function transferir(
        int $cuentaOrigenId,
        string $numeroCuentaDestino,
        string $valorTexto,
        string $contrasena,
    ): void {
        $this->servicioAutenticacion->reconfirmarContrasena($cuentaOrigenId, $contrasena);

        $cuentaDestino = $this->repositorioCuenta->buscarPorNumero($numeroCuentaDestino);
        if ($cuentaDestino === null) {
            throw new CuentaNoEncontradaException('La cuenta destino no existe');
        }

        $this->validarValorPositivo($valorTexto);

        if ($cuentaDestino->id === $cuentaOrigenId) {
            throw new ValorInvalidoException('La cuenta destino debe ser diferente a la cuenta origen');
        }

        $saldoOrigen = $this->repositorioCuenta->obtenerSaldo($cuentaOrigenId);
        if ((float) $saldoOrigen < (float) $valorTexto) {
            throw new SaldoInsuficienteException('El saldo disponible no alcanza para esta transferencia');
        }

        $this->conexion->beginTransaction();
        try {
            $this->repositorioCuenta->descontarSaldo($cuentaOrigenId, $valorTexto);
            $this->repositorioCuenta->abonarSaldo($cuentaDestino->id, $valorTexto);
            $this->repositorioTransferencia->registrar($cuentaOrigenId, $cuentaDestino->id, $valorTexto);
            $this->conexion->commit();
        } catch (Throwable $error) {
            $this->conexion->rollBack();
            throw $error;
        }
    }

    /**
     * @return array{cantidad: int, total: string, retiros: \App\Modelos\Retiro[]}
     */
    public function historialRetiros(int $cuentaId): array
    {
        $resumen = $this->repositorioRetiro->resumenPorCuenta($cuentaId);
        $resumen['retiros'] = $this->repositorioRetiro->listarPorCuenta($cuentaId);

        return $resumen;
    }

    /**
     * @return array{cantidad: int, total: string, transferencias: \App\Modelos\Transferencia[]}
     */
    public function historialTransferenciasEnviadas(int $cuentaId): array
    {
        $resumen = $this->repositorioTransferencia->resumenEnviadasPorCuenta($cuentaId);
        $resumen['transferencias'] = $this->repositorioTransferencia->listarEnviadasPorCuenta($cuentaId);

        return $resumen;
    }

    private function validarValorPositivo(string $valorTexto): void
    {
        if ($valorTexto === '' || !is_numeric($valorTexto) || (float) $valorTexto <= 0) {
            throw new ValorInvalidoException('El valor debe ser numérico y mayor que 0');
        }
    }
}
