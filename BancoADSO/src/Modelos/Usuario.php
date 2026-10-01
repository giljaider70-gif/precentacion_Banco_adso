<?php

declare(strict_types=1);

namespace App\Modelos;


final class Usuario
{
    public function __construct(
        public readonly int $id,
        public readonly int $cuentaId,
        public readonly string $claveHash,
    ) {
    }
}
