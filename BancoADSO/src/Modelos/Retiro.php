<?php

declare(strict_types=1);

namespace App\Modelos;

final class Retiro
{
    public function __construct(
        public readonly int $id,
        public readonly int $cuentaId,
        public readonly string $valor,
        public readonly string $fecha,
    ) {
    }
}
