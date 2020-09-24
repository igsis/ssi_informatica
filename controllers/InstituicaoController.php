<?php
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
} else {
    require_once "./models/MainModel.php";
}

class InstituicaoController extends MainModel
{
    /** <p>Retorna um array com todas instituições cadastradas</p>
     * @return array
     */
    public function listaInstituicoes()
    {
        return DbModel::consultaSimples("SELECT * FROM instituicoes")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaAdmins($id)
    {

        $sql = "SELECT u.nome FROM administrador_instituicao AS ai
                INNER JOIN usuarios AS u ON ai.administrador_id = u.id
                WHERE ai.instituicao_id = {$id}";
        return DbModel::consultaSimples($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function recuperaAdminsJson($idInstituicao)
    {
        $id = MainModel::decryption($idInstituicao);

        $sql = "SELECT u.id, u.nome FROM administrador_instituicao AS ai
                INNER JOIN usuarios AS u ON ai.administrador_id = u.id
                WHERE ai.instituicao_id = {$id}";
        $resultado = DbModel::consultaSimples($sql)->fetchAll(PDO::FETCH_OBJ);
        return json_encode($resultado);
    }
}