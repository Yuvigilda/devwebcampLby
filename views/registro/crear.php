<main>
    <h2 class="registro__heading">
        <?php echo $titulo; ?>
    </h2>
    <p class="registro__descripcion">
        Elige tu plan
    </p>
    <div class="paquetes__grid">
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Gratis</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">
                    Acceso Virtual a DevWebCamp
                </li>
            </ul>
            <p class="paquete__precio">$0</p>
            <form action="/finalizar-registro/gratis" method="POST">
                <input type="submit" value="Inscripcion Gratis" class="paquetes__submit">
            </form>
        </div>
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Presencial</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento"> Acceso Presencial a DevWebCamp</li>
                <li class="paquete__elemento"> Pase por 2 dias</li>
                <li class="paquete__elemento"> Acceso Talleres y Conferencias </li>
                <li class="paquete__elemento"> Acceso a las grabaciones </li>
                <li class="paquete__elemento"> Playera del evento </li>
                <li class="paquete__elemento"> Comida y vevida </li>

            </ul>
            <p class="paquete__precio">$199</p>
            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container"></div>
                </div>
            </div>


        </div>

        <div class="paquete">
            <h3 class="paquete__nombre">Pase Virtual</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento"> Acceso Presencial a DevWebCamp</li>
                <li class="paquete__elemento"> Pase por 2 dias</li>
                <li class="paquete__elemento"> Acceso Talleres y conferencias </li>
                <li class="paquete__elemento"> Acceso a las grabaciones </li>

            </ul>
            <p class="paquete__precio">$49</p>
            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container-virtual"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://www.paypal.com/sdk/js?client-id=AZ_4Kg93Ze6qDPSCUTR53I8jpcOaHvqBEc92IJCPgxETO8o5pXJdW0BKyzLZo673TxtP6nAMxB9bUEcH&currency=MXN"></script>
<script>
    function initPaypalButtons() {
        paypal.Buttons({
            style: {
                shape: 'rect',
                color: 'blue',
                layout: 'vertical',
                label: 'pay'

            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        "description": "1",
                        "amount": {
                            "currency_code": "MXN",
                            "value": 199
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    // debugger;
                    // console.log(orderData);
                    const datos = new FormData();
                    datos.append('paquete_id', orderData.purchase_units[0].description)
                    datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id)
                    // ruta que va procesar los datos
                    // debugger;
                    fetch('/finalizar-registro/pagar', {
                            method: 'POST',
                            body: datos
                        }).then(respuesta => respuesta.json()) //array function
                        .then(resultado => {
                            if (resultado.resultado && resultado.redirect) {
                                window.location.href = resultado.redirect;
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al procesar el pago',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                })

            },
            onError: function(err) {
                console.log(err);
            }
        }).render('#paypal-button-container')


        paypal.Buttons({
            style: {
                shape: 'rect',
                color: 'blue',
                layout: 'vertical',
                label: 'pay'

            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        "description": "2",
                        "amount": {
                            "currency_code": "MXN",
                            "value": 49
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    // debugger;
                    // console.log(orderData);
                    const datos = new FormData();
                    datos.append('paquete_id', orderData.purchase_units[0].description)
                    datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id)
                    // ruta que va procesar los datos
                    // debugger;
                    fetch('/finalizar-registro/pagar', {
                            method: 'POST',
                            body: datos
                        }).then(respuesta => respuesta.json()) //array function
                        .then(resultado => {
                            if (resultado.resultado && resultado.redirect) {
                                window.location.href = resultado.redirect;
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al procesar el pago',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                })

            },
            onError: function(err) {
                console.log(err);
            }
        }).render('#paypal-button-container-virtual')
    }
    initPaypalButtons();
</script>