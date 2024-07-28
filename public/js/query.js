//*******************************************CODE READ*********************************************//

$(document).ready(function () {
    //document.getElementById("txt_usuario").focus();

    $("#txt_codigo").keypress(function (e) {
        var inputStart,
            inputStop,
            firstKey,
            lastKey,
            timing,
            userFinishedEntering;
        var minChars = 3;

        $("#txt_codigo").keypress(function (e) {
            if (timing) {
                clearTimeout(timing);
            }

            if (e.which == 13) {
                e.preventDefault();

                if ($("#txt_codigo").val().length >= minChars) {
                    userFinishedEntering = true;
                    inputComplete();
                }
            } else {
                inputStop = performance.now();
                lastKey = e.which;

                userFinishedEntering = false;

                if (!inputStart) {
                    firstKey = e.which;
                    inputStart = inputStop;

                    $("body").on("blur", "#txt_codigo", inputBlur);
                }

                timing = setTimeout(inputTimeoutHandler, 500);
            }
        });

        function inputBlur() {
            clearTimeout(timing);
            if ($("#txt_codigo").val().length >= minChars) {
                userFinishedEntering = true;
                inputComplete();
            }
        }

        $("#reset").click(function (e) {
            e.preventDefault();
            resetValues();
        });

        function resetValues() {
            inputStart = null;
            inputStop = null;
            firstKey = null;
            lastKey = null;

            inputComplete();
        }

        function isScannerInput() {
            return (
                (inputStop - inputStart) / $("#txt_codigo").val().length < 15
            );
        }

        function isUserFinishedEntering() {
            return !isScannerInput() && userFinishedEntering;
        }

        function inputTimeoutHandler() {
            clearTimeout(timing);

            if (
                !isUserFinishedEntering() ||
                $("#txt_codigo").val().length < 3
            ) {
                return;
            } else {
                reportValues();
            }
        }

        function inputComplete() {
            txt_cod.value =
                txt_codigo.value; /*Asignar valor a txt_cod para que se imprima*/

            /* perdida de foco automatico */
            document.getElementById("txt_codigo").blur();
        }
    });
});

//*******************************************CODE READ*********************************************//

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

const buscar = () => {
    const cod = $("#txt_codigo").val();

    if (cod === "") {
        console.log("Codigo vacio");
    } else {
        const json = "codigo=" + cod;

        $.ajax({
            type: "POST",
            url: "query",
            data: json,
            success: (data) => {
                $("#lb_descripcion").text(data.descripcion);
                $("#lb_precio").text(data.precio + " Gs.");

                document.getElementById("txt_codigo").value = "";
                document.getElementById("txt_codigo").focus();
            },
        });
    }
};

//---------------------------------------------------------------------------------------------------//

// const captura = () => {
//     const cod = $('#txt_codigo').val();
//     oculto.value = cod;
// }

/* Se utilizaba para rellenar un input hidden automaticamente con cada escritura del usuario
El problema es que no funciona al hacer 'copy/paste' o usar un escaner. */

//---------------------------------------------------------------------------------------------------//
