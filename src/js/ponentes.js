(function(){
    const ponentesinput = document.querySelector('#ponentes')

    //es el cuadro de busqueda
    if(ponentesinput){
        let ponentes = [];
        let ponentesFiltrados = [];
        const listadoPonentes = document.querySelector('#listado-ponentes')//selector de id
        const ponenteHidden = document.querySelector('[name="ponente_id"]')//selector de atributo
        obtenerPonentes()
        ponentesinput.addEventListener('input', buscarPonentes)

        if(ponenteHidden.value){
            //este es una funcion ifi
            (async()=>{
                const ponente = await obtenerPonente(ponenteHidden.value)
                const {nombre, apellido} = ponente
                //insertar en el html
                const ponenteDOM = document.createElement('LI')
                ponenteDOM.classList.add('listado-ponentes__ponente','listado-ponentes__ponente--seleccionado')
                ponenteDOM.textContent = `${nombre} ${apellido}`
                listadoPonentes.appendChild(ponenteDOM)
            })(); 
        }

        //crear una funcion asincrona para consultar una api  para mostrar un listado de ponentes
    async function obtenerPonentes() {
        const url = '/api/ponentes';
        const respuesta = await fetch(url);//feach es medio de transporte para procesar los datos que vienen en el url
        const resultado = await respuesta.json()

        formatearPonetes(resultado)
    }

    async function obtenerPonente() {
        const url = `/api/ponente?id=${id}`
        const respuesta = await fetch(url)
        const resultado = await respuesta.json()
        return resultado 
    }

    function formatearPonetes(arrayPonentes = []){
        ponentes = arrayPonentes.map(ponente =>{  //.map devuelve un nuevo arreglo 
            return {
                nombre :  `${ponente.nombre.trim()} ${ponente.apellido.trim()}`,//trim e spara recortar espacios en blancos al escribir datos
                id : ponente.id
                

            }
         })
    }
//buscar ponentes ignorando minusculas y mayuscular 
    function buscarPonentes(e){
        const busqueda = e.target.value
        if(busqueda.length > 3){ 
            const expresion = new RegExp(busqueda, "i")
            ponentesFiltrados = ponentes.filter(ponente =>{
                if(ponente.nombre.toLowerCase().search(expresion) !== -1){
                    return ponente
                }
            })
        }else{
            ponentesFiltrados = []
        }
        mostrarPonentes()
    }

    function mostrarPonentes(){
        while(listadoPonentes.firstChild){
            listadoPonentes.removeChild(listadoPonentes.firstChild)
        }
        if(ponentesFiltrados.length> 0){
            ponentesFiltrados.forEach(ponente =>{
                const ponenteHTML = document.createElement('LI')//crear una lista d eponentes para la busqueda
                ponenteHTML.classList.add('listado-ponentes__ponente')
                ponenteHTML.textContent = ponente.nombre
                ponenteHTML.dataset.ponenteId = ponente.id
                ponenteHTML.onclick = seleccionarPonente

                //añadir al dom
                listadoPonentes.appendChild(ponenteHTML)
            })
        }else{
            const noResultados = document.createElement('P')
            noResultados.classList.add('listado-ponentes__no-resultado')
            noResultados.textContent = 'No hay resultado coincidentes'
            listadoPonentes.appendChild(noResultados)
        }
    }
    function seleccionarPonente(e){
        const ponente = e.target
        const ponentePrevio = document.querySelector('.listado-ponentes__ponente--seleccionado')
        if(ponentePrevio){
            ponentePrevio.classList.remove('listado-ponentes__ponente--seleccionado')
        }
        ponente.classList.add('listado-ponentes__ponente--seleccionado')
        ponenteHidden.value = ponente.dataset.ponenteId
    }

    }


})();