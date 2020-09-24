<?php
$pedidoAjax = true;
require_once "../config/configGeral.php";

if (isset($_POST['_method'])) {
    require_once "../controllers/ChamadoController.php";
    $chamadoObj = new ChamadoController();

    if ($_POST['_method'] == "cadastrar") {
        echo $chamadoObj->insereChamado();
    } elseif ($_POST['_method'] == "editar") {
        echo $chamadoObj->editaChamado($_POST['id']);
    } elseif ($_POST['_method'] == "cadastrarFuncionario") {
        echo $chamadoObj->insereFuncionarioChamado();
    } elseif ($_POST['_method'] == "editarFuncionario") {
        echo $chamadoObj->editaFuncionarioChamado($_POST['id']);
    } elseif ($_POST['_method'] == "excluirFuncionario") {
        echo $chamadoObj->excluiFuncionarioChamado();
    }
} else {
    include_once "../config/destroySession.php";
}