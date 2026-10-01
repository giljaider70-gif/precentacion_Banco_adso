<?php 
$resumen = $resumen ?? ['cantidad' => 0, 'total' => 0, 'transferencias' => []]; 
?>
<section class="resumen">
    <p>Cantidad de transferencias enviadas: <strong><?= e((string) $resumen['cantidad']) ?></strong></p>
    <p>Suma total transferida: <strong>$<?= formatearDinero($resumen['total']) ?></strong></p>
</section>

<?php if ($resumen['cantidad'] === 0): ?>
    <p class="mensaje">Todavía no has enviado transferencias.</p>
<?php else: ?>
    <table class="tabla-historial">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cuenta destino</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumen['transferencias'] as $transferencia): ?>
                <tr>
                    <td><?= e($transferencia->fecha) ?></td>
                    <td><?= e((string) $transferencia->cuentaDestinoId) ?></td>
                    <td>$<?= formatearDinero($transferencia->valor) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<nav class="acciones-secundarias">
    <a href="index.php?ruta=panel/index">&larr; Volver al panel</a>
    <a href="index.php?ruta=login/salir">Cerrar sesión</a>
</nav>
