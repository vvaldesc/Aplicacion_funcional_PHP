<?php

function mensajeError($message) {
    return '<nav class="navbar bg-body-tertiary bg-danger rounded m-2">
                <div class="container-fluid">
                    <p>
                        '. $message .'
                    </p>
                </div>
            </nav>';
}