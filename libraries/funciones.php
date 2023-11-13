<?php

//No se si esto es necesario - Víctor
if (!function_exists('mensajeError')) {
    function mensajeError($message) {
    
    return '<nav class="navbar bg-body-tertiary bg-danger rounded m-2">
            <div class="container-fluid">
                <p>
                    '. $message .'
                </p>
            </div>
        </nav>';
}
}

function generaToken(&$token,$session_id) {
    $hora = date('H:i'); 
    $token=hash('sha256', $hora.$session_id);    
}