
<?php 
$contenido = $contenido ?? ''; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloPagina ?? 'Banco ADSO') ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<?php if (isset($_SESSION['cuenta_id'])): ?>
    <header class="barra">
        <div class="marca">Banco ADSO</div>
        <nav class="navegacion">
            <a href="index.php?ruta=panel/index">Panel</a>
            <a href="index.php?ruta=retiro/formulario">Retirar</a>
            <a href="index.php?ruta=retiro/historial">Historial de retiros</a>
            <a href="index.php?ruta=transferencia/formulario">Transferir</a>
            <a href="index.php?ruta=transferencia/historial">Historial de transferencias</a>
            <a href="index.php?ruta=login/salir" class="enlace-salir">Salir</a>
        </nav>
    </header>
<?php endif; ?>

<main class="contenedor">
    <h1><?= e($tituloPagina ?? '') ?></h1>
    <?= $contenido ?>
</main>

<footer class="pie">
    <p>Banco ADSO &mdash; Proyecto integrador ADSO/SENA</p>
</footer>
</body>
</html>
