<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Recupera tu acceso a DevWebCamp</p>

    <?php require __DIR__ . '/../templates/alertas.php'; ?>

    <form action="/olvide" method="POST" class="formulario">
        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email</label>
            <input type="email" class="formulario__input" placeholder="Tu email" id="email" name="email">
        </div>
        <input type="submit" class="formulario__submit" value="Enviar Instrucciones">
    </form>
       <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes una cuenta? Inicia sesión</a>
        <a href="/registro" class="acciones__enlace">Aun no tienes un cuenta?, Obtener una</a>
    </div>
</main>