<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Usuario;
use PDO;


final class RepositorioUsuario
{
    public function __construct(private readonly PDO $conexion)
    {
    }

    public function buscarPorCuentaId(int $cuentaId): ?Usuario
    {
        $sentencia = $this->conexion->prepare(
            'SELECT id, cuenta_id, clave_hash FROM usuarios WHERE cuenta_id = :cuenta_id',
        );
        $sentencia->execute(['cuenta_id' => $cuentaId]);
        $fila = $sentencia->fetch();

        if ($fila === false) {
            return null;
        }

        return new Usuario((int) $fila['id'], (int) $fila['cuenta_id'], (string) $fila['clave_hash']);
    }
}
