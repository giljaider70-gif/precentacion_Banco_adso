<?php 
$resumen = $resumen ?? ['cantidad' => 0, 'total' => 0, 'retiros' => []]; 
?>
<section class="resumen">
    <p>Número total de retiros: <strong><?= e((string) $resumen['cantidad']) ?></strong></p>
    <p>Suma total retirada: <strong>$<?= formatearDinero($resumen['total']) ?></strong></p>
</section>

<?php if ($resumen['cantidad'] === 0): ?>
    <p class="mensaje">Todavía no tienes retiros registrados.</p>
<?php else: ?>
    <table class="tabla-historial">
        <thead>      
            <tr>
                <th>Fecha</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumen['retiros'] as $retiro): ?>
                <tr>
                    <td><?= e($retiro->fecha) ?></td>
                    <td>$<?= formatearDinero($retiro->valor) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<nav class="acciones-secundarias">
    <a href="index.php?ruta=panel/index">&larr; Volver al panel</a>
    <a href="index.php?ruta=login/salir">Cerrar sesión</a>
</nav>
