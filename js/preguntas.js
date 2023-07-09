function verificarRespuesta(elemento, respuestaSeleccionada, preguntaId) {
    // Obtener todas las opciones
    var opciones = document.getElementsByClassName("opcion");

    // Quitar la clase seleccionada a todas las opciones
    for (var i = 0; i < opciones.length; i++) {
        opciones[i].classList.remove("seleccionada");
    }

    // Agregar la clase seleccionada a la opción clicada
    elemento.classList.add("seleccionada");

    // Enviar la respuesta seleccionada al servidor
    var formulario = document.createElement("form");
    formulario.method = "post";
    formulario.action = "";

    var preguntaIdInput = document.createElement("input");
    preguntaIdInput.type = "hidden";
    preguntaIdInput.name = "pregunta_id";
    preguntaIdInput.value = preguntaId;
    formulario.appendChild(preguntaIdInput);

    var respuestaSeleccionadaInput = document.createElement("input");
    respuestaSeleccionadaInput.type = "hidden";
    respuestaSeleccionadaInput.name = "respuesta_seleccionada";
    respuestaSeleccionadaInput.value = respuestaSeleccionada;
    formulario.appendChild(respuestaSeleccionadaInput);

    document.body.appendChild(formulario);
    formulario.submit();
}