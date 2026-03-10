<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a href="/admin/ponentes?page=0" class="dashboard__boton">
        <i class="fa-solid fa-circle-arrow-left">
            Volver
        </i>
    </a>
</div>

<div class="dashboard__formulario">
    <?php 
    include_once __DIR__ . '/../../templates/alertas.php';
     ?>
     <!-- multipart/form-data es quien activa la super globar _FILES -->
     <form method="POST" class="formulario" enctype="multipart/form-data"> 
        <?php include_once __DIR__ . '/formulario.php' ?>
        <input type="submit" class="formulario__submit formulario__submit--registrar" value="Actualizar ponente">
     </form>
</div>