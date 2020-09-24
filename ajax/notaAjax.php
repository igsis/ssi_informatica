<?php
$pedidoAjax = true;
require_once "../config/configGeral.php";

if (isset($_POST['_method'])) {
    require_once "../controllers/NotaController.php";
    $notaObj = new NotaController();

    if ($_POST['_method'] == "cadastrar") {
        echo $notaObj->insereNota();
    }

} else {
    include_once "../config/destroySession.php";
}