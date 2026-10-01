<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Cliente;
use PDO;


final class RepositorioCliente
{
    public function __construct(private readonly PDO $conexion)
    {
    }

    public function buscarPorId(int $id): ?Cliente
    {
        $sentencia = $this->conexion->prepare(
            'SELECT id, nombre FROM clientes WHERE id = :id',
        );
        $sentencia->execute(['id' => $id]);
        $fila = $sentencia->fetch();

        if ($fila === false) {
            return null;
        }

        return new Cliente((int) $fila['id'], (string) $fila['nombre']);
    }
}
