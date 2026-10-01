<?php 
$mensajeExito = $mensajeExito ?? '';
$cuenta = $cuenta ?? (object)['numeroCuenta' => 'N/A']; 
$saldo = $saldo ?? 0.00; 

?>
<?php if (!empty($mensajeExito)): ?>
    <p class="mensaje mensaje-exito"><?= e($mensajeExito) ?></p>
<?php endif; ?>

<section class="tarjeta-saldo">
    <p class="etiqueta">Cuenta</p>
    <p class="numero-cuenta"><?= e($cuenta->numeroCuenta) ?></p>

    <p class="etiqueta">Saldo disponible</p>
    <p class="saldo">$<?= formatearDinero($saldo) ?></p>
</section>

<section class="accesos-rapidos">
    <a href="index.php?ruta=retiro/formulario" class="boton">Realizar retiro</a>
    <a href="index.php?ruta=transferencia/formulario" class="boton">Realizar transferencia</a>
</section>

<nav class="acciones-secundarias">
    <a href="index.php?ruta=login/salir">Cerrar sesión</a>
</nav>
