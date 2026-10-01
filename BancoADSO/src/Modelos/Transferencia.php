<?php

declare(strict_types=1);

namespace App\Modelos;

final class Transferencia
{
    public function __construct(
        public readonly int $id,
        public readonly int $cuentaOrigenId,
        public readonly int $cuentaDestinoId,
        public readonly string $valor,
        public readonly string $fecha,
    ) {
    }
}
