<?php if (!empty($error)): ?>
    <p class="mensaje mensaje-error"><?= e($error) ?></p>
<?php endif; ?>

<form action="index.php?ruta=login/procesar" method="post" class="formulario">
    <label for="numero_cuenta">Número de cuenta</label>
    <input type="text" id="numero_cuenta" name="numero_cuenta" required autofocus autocomplete="username">

    <label for="contrasena">Contraseña</label>
    <input type="password" id="contrasena" name="contrasena" required autocomplete="current-password">

    <button type="submit">Iniciar sesión</button>
</form>
