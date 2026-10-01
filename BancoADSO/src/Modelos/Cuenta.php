<?php

declare(strict_types=1);

namespace App\Modelos;


final class Cuenta
{
    public function __construct(
        public readonly int $id,
        public readonly string $numeroCuenta,
        public readonly string $saldo,
        public readonly int $clienteId,
    ) {
    }
}
