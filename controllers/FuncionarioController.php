<?php
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
} else {
    require_once "./models/MainModel.php";
}

class FuncionarioController extends MainModel
{
    public function insereFuncionario()
    {
        /* executa limpeza nos campos */
        $dados = [];
        unset($_POST['_method']);
        foreach ($_POST as $campo => $post) {
            $dados[$campo] = MainModel::limparString($post);
        }
        /* ./limpeza */

        /* cadastro */
        $insere = DbModel::insert('funcionarios', $dados);
        if ($insere->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $id = DbModel::connection()->lastInsertId();
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Funcionário',
                'texto' => 'Funcionário cadastrado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/funcionario_cadastro&id='.MainModel::encryption($id)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . '/funcionario_cadastro'
            ];
        }
        /* ./cadastro */
        return MainModel::sweetAlert($alerta);
    }

    /* edita */
    public function editaFuncionario($id)
    {
        $idDecryp = MainModel::decryption($id);
        unset($_POST['_method']);
        unset($_POST['id']);

        $dados = [];
        foreach ($_POST as $campo => $post) {
            $dados[$campo] = MainModel::limparString($post);
        }

        $edita = DbModel::update('funcionarios', $dados, $idDecryp);
        if ($edita->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Funcionário',
                'texto' => 'Funcionário editado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/funcionario_cadastro&id='.MainModel::encryption($idDecryp)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/funcionario_cadastro&id='.MainModel::encryption($idDecryp)
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function apagar($id)
    {
        $id = MainModel::decryption($id);
        $apagar = DbModel::apaga('funcionarios', $id);
        if ($apagar->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Funcionário',
                'texto' => 'Funcionário apagado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL .'administrador/funcionarios'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao apagar funcionário!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/funcionarios'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function listarFuncionario()
    {
        $funcionarios = DbModel::consultaSimples("SELECT * FROM funcionarios WHERE publicado = 1")->fetchAll(PDO::FETCH_OBJ);
        return $funcionarios;
    }

    public function recuperaFuncionario($id)
    {
        $id = MainModel::decryption($id);
        $usuario = DbModel::getInfo('funcionarios', $id);
        return $usuario->fetchObject();
    }
}