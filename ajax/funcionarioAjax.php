<?php
$pedidoAjax = true;
require_once "../config/configGeral.php";

if (isset($_POST['_method'])) {
    require_once "../controllers/FuncionarioController.php";
    $insChamado = new FuncionarioController();

    if ($_POST['_method'] == "cadastrar") {
        echo $insChamado->insereFuncionario();
    } elseif ($_POST['_method'] == "editar") {
        echo $insChamado->editaFuncionario($_POST['id']);
    }elseif($_POST['_method'] == "remover"){
        echo $insChamado->apagar($_POST['id']);
    }

} else {
    include_once "../config/destroySession.php";
}