<?php
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
} else {
    require_once "./models/MainModel.php";
}

class UsuarioModel extends MainModel
{
    protected function getUsuario($dados) {
        $pdo = parent::connection();
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :senha";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":usuario", $dados['usuario']);
        $statement->bindParam(":senha", $dados['senha']);
        $statement->execute();
        return $statement;
    }

    protected function getEmail($dados) {
        $pdo = parent::connection();
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":email", $dados['email']);
        $statement->execute();
        return $statement;
    }

    protected function getExisteEmail($email){
        $query = "SELECT id, email2 FROM usuarios WHERE email2 = '$email'";
        $resultado = DbModel::consultaSimples($query);
        return $resultado;
    }

    protected function getInstituicaoTecnico($id)
    {
        return DbModel::consultaSimples("SELECT tecnico_id FROM tecnico_instituicao WHERE tecnico_id = '$id'")->rowCount();
    }

    protected function insereTecnicoInstituicao($id)
    {
        $dados['instituicao_id'] = DbModel::consultaSimples("SELECT instituicao_id FROM usuarios WHERE id = '$id'")->fetchColumn();
        $dados['tecnico_id'] = $id;

        DbModel::insert('tecnico_instituicao', $dados);
    }
}