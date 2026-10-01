<?php if (!empty($error)): ?>
    <p class="mensaje mensaje-error"><?= e($error) ?></p>
<?php endif; ?>

<form action="index.php?ruta=retiro/procesar" method="post" class="formulario">
    <label for="valor">Valor a retirar</label>
    <input type="number" id="valor" name="valor" min="0.01" step="0.01" required autofocus>

    <label for="contrasena">Confirma tu contraseña</label>
    <input type="password" id="contrasena" name="contrasena" required autocomplete="current-password">

    <button type="submit">Confirmar retiro</button>
</form>

<nav class="acciones-secundarias">
    <a href="index.php?ruta=panel/index">&larr; Volver al panel</a>
    <a href="index.php?ruta=login/salir">Cerrar sesión</a>
</nav>
