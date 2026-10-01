<?php

declare(strict_types=1);

namespace App\Nucleo;

use PDO;
use PDOException;

final class Conexion
{
    private static ?PDO $instancia = null;

    public static function obtenerInstancia(): PDO
    {
        if (self::$instancia === null) {
            $config = require __DIR__ . '/../../config/basedatos.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['puerto'],
                $config['nombre_bd'],
                $config['charset']
            );

            try {
                self::$instancia = new PDO($dsn, $config['usuario'], $config['clave'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                exit('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }

    public static function obtener(): PDO
    {
        return self::obtenerInstancia();
    }
}