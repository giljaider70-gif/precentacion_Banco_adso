<?php

declare(strict_types=1);


function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function formatearDinero(string $valor): string
{
    return number_format((float) $valor, 2, ',', '.');
}
