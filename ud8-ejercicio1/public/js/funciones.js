function validarFecha() {
    // Obtiene el valor del campo de entrada con id 'title'
    let fecha = document.getElementById('title').value;

    // Verifica si el campo está vacío
    if (fecha.length == 0) {
        alert("Elija una fecha.");
        return false;
    }

    // Crea un objeto Date con la fecha ingresada por el usuario
    let miFecha = new Date(fecha);
    let hoy     = new Date();

    // Ajusta ambas fechas a medianoche para comparar solo la fecha, sin horas
    hoy.setHours(0, 0, 0, 0);
    miFecha.setHours(0, 0, 0, 0);

    // Verifica si la fecha ingresada es anterior a la fecha actual
    if (miFecha < hoy) {
        alert("La fecha no puede ser inferior a la actual");
        return false;
    }

    return true; // La fecha es válida
}

function getCoordenadas() {
    // Obtiene la dirección ingresada en el campo con id 'dir'
    let dir = document.getElementById('dir').value;

    // Llama a la función de Xajax para obtener las coordenadas de la dirección
    jaxon_getCoordenadas(dir);
    return true;
}

function ordenarEnvios(id) {
    // Obtiene los valores ocultos dentro del elemento con id 't_' + id
    var puntos = $("#t_" + id + " input:hidden").map(function () {
        return this.value;
    }).get().join("|"); // Une los valores con '|'

    // Llama a la función de Xajax para ordenar los envíos según los puntos obtenidos
    jaxon_ordenarEnvios(puntos, id);
}

// En tu funciones.js, actualiza obtuvimosDatos():
function obtuvimosDatos(datosRespuesta) {
    if (datosRespuesta.respuesta === "404") {
        alert("Servicio para ordenar Rutas no disponible");
        return;
    }
    
    // Nueva validación de estructura de datos
    if (!datosRespuesta.id || !datosRespuesta.respuesta) {
        console.error('Estructura de datos inválida:', datosRespuesta);
        alert('Error en el formato de la respuesta');
        return;
    }

    // Construcción de URL más robusta
    const url = new URL(window.location.origin + '/tarea08/public/repartos.php');
    url.searchParams.append('action', 'oEnvios');
    url.searchParams.append('idLt', datosRespuesta.id);
    datosRespuesta.respuesta.forEach(pos => {
        url.searchParams.append('pos[]', pos);
    });

    window.location.href = url.toString();
}

function semaforo() {
    // Obtiene los valores de los campos de entrada de latitud y producto
    var latitud = document.getElementById('lat').value;
    var pro     = document.getElementById("pro").value;

    // Verifica si se ingresó una latitud y si se seleccionó un producto válido
    if (latitud.length == 0 || pro == -1) {
        return false;
    }

    return true;
}