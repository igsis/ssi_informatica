<?php
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
} else {
    require_once "./models/MainModel.php";
}

class InstituicaoModel extends MainModel
{

    protected function getAdministrador($instituicao) {
        $pdo = parent::connection();
        $sql = "SELECT  us.id ,us.nome
                FROM instituicoes AS ins  
                LEFT JOIN administrador_instituicao AS ad ON ins.id = ad.instituicao_id
                LEFT JOIN usuarios AS us ON ad.administrador_id = us.id
                WHERE ins.id = :instituicao";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":instituicao", $instituicao);
        $statement->execute();
        return $statement;
    }

}