<h2 class="dashboard__heading"><?php echo $titulo;?></h2>
<div class="dashboard__contenedor-boton">
    <a href="/admin/ponentes" class="dashboard__boton">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>
</div>
<div class="dashboard__formulario">
    <?php 
    include_once __DIR__ . '/../../templates/alertas.php';
     ?>
     <form action="/admin/ponentes/crear" method="POST" class="formulario" enctype="multipart/form-data">
        <?php include_once __DIR__ . '/formulario.php' ?>
        <input class="formulario__submit formulario__submit--registrar" type="submit" value="registrar Ponente">
     </form>
</div>