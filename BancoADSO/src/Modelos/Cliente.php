<?php

declare(strict_types=1);

namespace App\Modelos;


final class Cliente
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
    ) {
    }
}
