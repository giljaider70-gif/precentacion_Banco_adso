<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Cuenta;
use PDO;


final class RepositorioCuenta
{
    public function __construct(private readonly PDO $conexion)
    {
    }

    public function buscarPorId(int $id): ?Cuenta
    {
        $sentencia = $this->conexion->prepare(
            'SELECT id, numero_cuenta, saldo, clientes_id FROM cuentas WHERE id = :id',
        );
        $sentencia->execute(['id' => $id]);

        return $this->mapearFila($sentencia->fetch());
    }

    public function buscarPorNumero(string $numeroCuenta): ?Cuenta
    {
        $sentencia = $this->conexion->prepare(
            'SELECT id, numero_cuenta, saldo, clientes_id FROM cuentas WHERE numero_cuenta = :numero_cuenta',
        );
        $sentencia->execute(['numero_cuenta' => $numeroCuenta]);

        return $this->mapearFila($sentencia->fetch());
    }

    public function existeCuenta(int $id): bool
    {
        $sentencia = $this->conexion->prepare('SELECT 1 FROM cuentas WHERE id = :id');
        $sentencia->execute(['id' => $id]);

        return $sentencia->fetch() !== false;
    }

    /**
     * Lee el saldo actual siempre en vivo desde base de datos (RN8):
     * nunca se reutiliza un valor guardado en sesión.
     */
    public function obtenerSaldo(int $id): string
    {
        $sentencia = $this->conexion->prepare('SELECT saldo FROM cuentas WHERE id = :id');
        $sentencia->execute(['id' => $id]);
        $fila = $sentencia->fetch();

        return $fila === false ? '0.00' : (string) $fila['saldo'];
    }

    /**
     * La aritmética de dinero la hace la propia base de datos
     * (saldo = saldo - :valor) para evitar sumas/restas con float en PHP.
     */
    public function descontarSaldo(int $id, string $valor): void
    {
        $sentencia = $this->conexion->prepare(
            'UPDATE cuentas SET saldo = saldo - :valor WHERE id = :id',
        );
        $sentencia->execute(['valor' => $valor, 'id' => $id]);
    }

    public function abonarSaldo(int $id, string $valor): void
    {
        $sentencia = $this->conexion->prepare(
            'UPDATE cuentas SET saldo = saldo + :valor WHERE id = :id',
        );
        $sentencia->execute(['valor' => $valor, 'id' => $id]);
    }

    private function mapearFila(array|false $fila): ?Cuenta
    {
        if ($fila === false) {
            return null;
        }

        return new Cuenta(
            (int) $fila['id'],
            (string) $fila['numero_cuenta'],
            (string) $fila['saldo'],
            (int) $fila['clientes_id'],
        );
    }
}
