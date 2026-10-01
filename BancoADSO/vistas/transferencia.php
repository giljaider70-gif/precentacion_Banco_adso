<?php if (!empty($error)): ?>
    <p class="mensaje mensaje-error"><?= e($error) ?></p>
<?php endif; ?>

<form action="index.php?ruta=transferencia/procesar" method="post" class="formulario">
    <label for="numero_cuenta_destino">Cuenta destino</label>
    <input type="text" id="numero_cuenta_destino" name="numero_cuenta_destino" required autofocus>

    <label for="valor">Valor a transferir</label>
    <input type="number" id="valor" name="valor" min="0.01" step="0.01" required>

    <label for="contrasena">Confirma tu contraseña</label>
    <input type="password" id="contrasena" name="contrasena" required autocomplete="current-password">

    <button type="submit">Confirmar transferencia</button>
</form>

<nav class="acciones-secundarias">
    <a href="index.php?ruta=panel/index">&larr; Volver al panel</a>
    <a href="index.php?ruta=login/salir">Cerrar sesión</a>
</nav>
