$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

const buscar = () => {

    const cod = $('#txt_codigo').val();

    if (cod === "") {
        console.log("Codigo vacio");
    } else {

        const json = "codigo=" + cod;

        $.ajax({
            type: "POST",
            url: "query",
            data: json,
            success: (data) => {
                $('#lb_descripcion').text(data.descripcion);
                $('#lb_precio').text(data.precio + ' Gs.');                

            }
        })
    }
}

//---------------------------------------------------------------------------------------------------//

const captura = () => {
    const cod = $('#txt_codigo').val();
    oculto.value = cod;
}

//---------------------------------------------------------------------------------------------------//