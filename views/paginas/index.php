<section class="resumen">
    <div class="resumen__grid">
        <div <?php AOS_animacion(); ?> class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero"><?php echo $ponentes_total; ?></p>
            <p class="resumen__texto">Speakers</p>
        </div>
        <div <?php AOS_animacion(); ?> class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero"><?php echo $conferencias_total; ?></p>
            <p class="resumen__texto">Conferencias</p>
        </div>
        <div <?php AOS_animacion(); ?> class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero"><?php echo $workshops_total; ?></p>
            <p class="resumen__texto">Workshops</p>
        </div>
        <div <?php AOS_animacion(); ?> class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">500</p>
            <p class="resumen__texto">Asistentes</p>
        </div>
    </div>
</section>

<section class="speakers">
    <h2 class="speakers__heading">Speakers</h2>
    <p class="speakers__descripcion">
        Conoce a nuestros expertos de DevWebCamp
    </p>
    <div class="speakers__grid">
        <?php foreach ($ponentes as $ponente) { ?>
            <div <?php AOS_animacion(); ?> class="speaker">

                <picture>
                    <source srcset="img/speakers/<?php echo $ponente->imagen; ?>.webp" type="image/webp">
                    <source srcset="img/speakers/<?php echo $ponente->imagen; ?>.png" type="image/png">
                    <img class="speaker__imagen" loading="lazy" src="img/speakers/<?php echo $ponente->imagen; ?>.png" width="500" height="300" alt="imagen ponente">
                </picture>
                <div class="speakers__informacion">
                    <h4 class="speakers__nombre"><?php echo $ponente->nombre . " " . $ponente->apellido; ?></h4>
                    <p class="speaker__ubicacion"><?php echo $ponente->ciudad . " " . $ponente->pais; ?></p>

                    <div class="speaker__sociales">
                        <!-- <?php $redes // = json_decode($ponente->redes); 
                                ?> -->
                    </div>
                    <ul class="speaker__listado-skills">
                        <?php $tags = explode(',', $ponente->tags);
                        foreach ($tags as $tag) { ?>
                            <li class="speaker__skill"><?php echo $tag; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<div class="mapa" id="mapa">

</div>
<section class="boletos">
    <h2 class="boletos__heading">Boletos & Precios</h2>
    <p class="boletos__descripcion">Precios para DevWebCamp</p>
    <div class="boletos__grid">
        <div class="boleto boleto--presencial">
            <h4 class="boleto__logo">&#60;DevWebCamp</h4>
            <p class="boleto__plan">Presencial</p>
            <p class="boleto__precio">$199</p>
        </div>


        <div class="boleto boleto--virtual">
            <h4 class="boleto__logo">&#60;DevWebCamp</h4>
            <p class="boleto__plan">Virtual</p>
            <p class="boleto__precio">$49</p>
        </div>
        <div class="boleto boleto--gratis">
            <h4 class="boleto__logo">&#60;DevWebCamp</h4>
            <p class="boleto__plan">Gratis</p>
            <p class="boleto__precio">$Gratis - $0</p>
        </div>
    </div>
    <div class="boleto__enlace-contenedor">
        <a href="/paquetes" class="boleto__enlace">Ver paquetes</a>
    </div>

</section>