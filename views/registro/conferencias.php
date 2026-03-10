<h2 class="pagina__heading"><?php echo $titulo; ?></h2>
<p class="pagina__descripcion">Eligehasta 5 eventos para asistir de forma presencial</p>
<div class="eventos-registro">
    <main class="eventos-registro__listado">
        <h3 class="eventos-registro__heading--conferencias">&lt;Conferencias /></h3>
        <p class="eventos-registro__fecha">Viernes 16 de enero</p>
        <div class="eventos-registro__grid">
            <?php foreach ($eventos['conferencias_v'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <p class="eventos-registro__fecha">Sabado 17 de enero</p>
        <div class="eventos-registro__grid">
            <?php foreach ($eventos['conferencias_v'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>
        <h3 class="eventos-registro__heading--workshops">&lt;Workshops/></h3>
        <p class="eventos-registro__fecha">Viernes 16 de enero</p>
        <div class="eventos-registro__grid">
            <?php foreach ($eventos['conferencias_v'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <p class="eventos-registro__fecha">Sabado 17 de enero</p>
        <div class="eventos-registro__grid">
            <?php foreach ($eventos['conferencias_v'] as $evento) { ?>
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>
    </main>
    <aside class="registro">
        <h2 class="registro__heading">Tu registro</h2>
        <div class="registro__resumen" id="registro-resumen">

        </div>
        <div class="registro__regalo">
            <label for="regalo" class="registro__label">Selecciona un regalo</label>
            <select class="regalo__select" id="regalo">
                <option value="">--Selecciona tu regalo--</option>+
                <?php
                foreach ($regalos as $regalo) { ?>
                    <option value="<?php echo $regalo->id; ?>"> <?php echo $regalo->nombre ?></option>
                <?php } ?>
            </select>
            <div>
                <form action="" id="registro" class="formulario">
                    <div class="formulario__campo">
                        <input type="submit" class="formulario__submit formulario__submit--full" value="Registrarme">
                    </div>
                </form>
            </div>
        </div>
    </aside>
</div>