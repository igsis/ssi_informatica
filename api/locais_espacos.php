<?php
$pedidoAjax = true;

require_once "../config/configGeral.php";
require_once "../config/configAPP.php";
require_once "../models/MainModel.php";

$db = new MainModel();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

if(isset($_GET['instituicao_id']) || isset($_POST['instituicao_id'])){
    $id = $_GET['instituicao_id'] ?? $_POST['instituicao_id'];

    $sql = "SELECT id, local FROM locais WHERE instituicao_id = '$id' AND publicado = 1 order by local";
    $res = $db->consultaSimples($sql)->fetchAll();

    $locais = json_encode($res);

    print_r($locais);
}