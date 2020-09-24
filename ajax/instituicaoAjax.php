<?php
$pedidoAjax = true;
require_once "../config/configGeral.php";

if (isset($_POST['_method'])) {
    require_once "../controllers/InstituicaoController.php";
    $instituicaoObj = new InstituicaoController();

    if($_POST['_method'] == 'recuperaAdministrador') {
        echo $instituicaoObj->recuperaAdminsJson($_POST['id']);
    }
} else {
    include_once "../config/destroySession.php";
}