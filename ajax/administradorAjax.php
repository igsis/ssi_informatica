<?php
$pedidoAjax = true;
require_once "../config/configGeral.php";

if (isset($_POST['_method'])) {
    require_once "../controllers/AdministradorController.php";
    $administradorObj = new AdministradorController();

    switch ($_POST['_method']) {
        case 'insereAdmin':
            echo $administradorObj->nivelAcesso(2);
            break;

        case 'removeAdmin':
            echo $administradorObj->nivelAcesso(1);
            break;

        case 'insereInstituicao':
            echo $administradorObj->insereInstituicao();
            break;

        case 'editaInstituicao':
            echo $administradorObj->editaInstituicao();
            break;

        case 'vinculaAdm':
            echo $administradorObj->vinculaAdm();
            break;

        case 'insereLocal':
            echo $administradorObj->insereLocal();
            break;

        case 'editaLocal':
            echo $administradorObj->editaLocal($_POST['id']);
            break;

        case 'removeLocal':
            echo $administradorObj->removeLocal($_POST['local_id']);
            break;

        case 'insereCategoria':
            echo $administradorObj->insereCategoria();
            break;

        case 'editaCategoria':
            echo $administradorObj->editaCategoria($_POST['categoria_id']);
            break;

        case 'removeCategoria':
            echo $administradorObj->removeCategoria($_POST['categoria_id']);
            break;
    }
} else {
    include_once "../config/destroySession.php";
}