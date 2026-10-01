<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Transferencia;
use PDO;


final class RepositorioTransferencia
{
    public function __construct(private readonly PDO $conexion)
    {
    }

    public function registrar(int $cuentaOrigenId, int $cuentaDestinoId, string $valor): void
    {
        $sentencia = $this->conexion->prepare(
            'INSERT INTO transferencias (cuenta_origen_id, cuenta_destino_id, valor, fecha)
             VALUES (:cuenta_origen_id, :cuenta_destino_id, :valor, NOW())',
        );
        $sentencia->execute([
            'cuenta_origen_id' => $cuentaOrigenId,
            'cuenta_destino_id' => $cuentaDestinoId,
            'valor' => $valor,
        ]);
    }

    /**
     * @return Transferencia[]
     */
    public function listarEnviadasPorCuenta(int $cuentaOrigenId): array
    {
        $sentencia = $this->conexion->prepare(
            'SELECT id, cuenta_origen_id, cuenta_destino_id, valor, fecha
             FROM transferencias WHERE cuenta_origen_id = :cuenta_origen_id ORDER BY fecha DESC',
        );
        $sentencia->execute(['cuenta_origen_id' => $cuentaOrigenId]);

        return array_map(
            static fn (array $fila): Transferencia => new Transferencia(
                (int) $fila['id'],
                (int) $fila['cuenta_origen_id'],
                (int) $fila['cuenta_destino_id'],
                (string) $fila['valor'],
                (string) $fila['fecha'],
            ),
            $sentencia->fetchAll(),
        );
    }

    /**
     * Solo lo que la cuenta envió (RF7): cantidad y suma total,
     * calculadas contra la tabla transferencias.
     *
     * @return array{cantidad: int, total: string}
     */
    public function resumenEnviadasPorCuenta(int $cuentaOrigenId): array
    {
        $sentencia = $this->conexion->prepare(
            'SELECT COUNT(*) AS cantidad, COALESCE(SUM(valor), 0) AS total
             FROM transferencias WHERE cuenta_origen_id = :cuenta_origen_id',
        );
        $sentencia->execute(['cuenta_origen_id' => $cuentaOrigenId]);
        $fila = $sentencia->fetch();

        return [
            'cantidad' => (int) $fila['cantidad'],
            'total' => (string) $fila['total'],
        ];
    }
}
