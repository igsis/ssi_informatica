<?php
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
} else {
    require_once "./models/MainModel.php";
}

class LocalModel extends MainModel
{
    protected function getLocal($dados) {
        $pdo = parent::connection();
        $sql = "SELECT * FROM locais WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":id", $dados['id']);
        $statement->execute();
        return $statement;
    }

    protected function getInstituicaoLocal($local_id){
        $pdo = parent::connection();
        $sql = "SELECT ins.instituicao
                FROM locais AS loc 
                INNER JOIN instituicoes AS ins ON ins.id = loc.instituicao_id
                WHERE loc.id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":id", $local_id);
        $statement->execute();
        return $statement;
    }

    protected function getLocalInstituicao($dados) {
        $pdo = parent::connection();
        $sql = "SELECT *
                FROM instituicoes AS ins 
                LEFT JOIN locais AS lo ON ins.id = lo.instituicao_id
                WHERE lo.id = :id OR ins.id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":id", $dados['id']);
        $statement->execute();
        return $statement;
    }

    protected function getAdministrador($dados) {
        $pdo = parent::connection();
        $sql = "SELECT ad.administrador_id
                FROM administrador_instituicao AS ad
                LEFT JOIN instituicoes AS ins ON ad.instituicao_id = ins.id
                LEFT JOIN locais AS lo ON ins.id = lo.instituicao_id
                LEFT JOIN usuarios AS us ON lo.id = us.local_id
                WHERE lo.id = :local OR us.id = :usuario";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":local", $dados['local']);
        $statement->bindParam(":usuario", $dados['usuario']);
        $statement->execute();
        return $statement;
    }
}