<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Retiro;
use PDO;

final class RepositorioRetiro
{
    public function __construct(private readonly PDO $conexion)
    {
    }
 
    public function registrar(int $cuentaId, string $valor): void
    {
        $sentencia = $this->conexion->prepare(
            'INSERT INTO retiros (cuentas_id, valor, fecha) VALUES (:cuenta_id, :valor, NOW())',
        );
        $sentencia->execute(['cuenta_id' => $cuentaId, 'valor' => $valor]);
    }
 
    /**
     * @return Retiro[]
     */
    public function listarPorCuenta(int $cuentaId): array
    {
        $sentencia = $this->conexion->prepare(
            'SELECT id, cuentas_id AS cuenta_id, valor, fecha FROM retiros WHERE cuentas_id = :cuenta_id ORDER BY fecha DESC',
        );
        $sentencia->execute(['cuenta_id' => $cuentaId]);
 
        return array_map(
            static fn (array $fila): Retiro => new Retiro(
                (int) $fila['id'],
                (int) $fila['cuenta_id'],
                (string) $fila['valor'],
                (string) $fila['fecha'],
            ),
            $sentencia->fetchAll(),
        );
    }
 
    /**
     * Cantidad y suma total calculadas directamente contra la tabla
     * retiros (RF5), nunca contra un contador aparte.
     *
     * @return array{cantidad: int, total: string}
     */
    public function resumenPorCuenta(int $cuentaId): array
    {
        $sentencia = $this->conexion->prepare(
            'SELECT COUNT(*) AS cantidad, COALESCE(SUM(valor), 0) AS total FROM retiros WHERE cuentas_id = :cuenta_id',
        );
        $sentencia->execute(['cuenta_id' => $cuentaId]);
        $fila = $sentencia->fetch();
 
        return [
            'cantidad' => (int) $fila['cantidad'],
            'total' => (string) $fila['total'],
        ];
    }
}