<?php
if ($pedidoAjax) {
    require_once "../controllers/UsuarioController.php";
} else {
    require_once "./controllers/UsuarioController.php";
}

class AdministradorController extends UsuarioController
{
    public function listaAdmins()
    {
        return DbModel::consultaSimples("SELECT * FROM usuarios WHERE publicado = 1 AND nivel_acesso_id = 2")->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * <p>Altera o nivel de acesso do usuario para 2 <i>(Administrador)</i> ou 1 <i>(Usuario)</i></p>
     * @return string
     */
    public function nivelAcesso($nvlAcesso)
    {
        $usuario_id = MainModel::limparString($_POST['usuario_id']);
        $usuario_id = MainModel::decryption($usuario_id);

        if ($nvlAcesso == 1) {
            $texto = 'O usuário selecionado removido do grupo de administradores!';
        } else {
            $texto = 'O usuário selecionado agora é um administrador!';
        }

        $update = DbModel::update('usuarios', ['nivel_acesso_id' => $nvlAcesso], $usuario_id);
        if ($update) {
            if ($nvlAcesso == 1) {
                DbModel::deleteEspecial('administrador_instituicao', 'administrador_id', $usuario_id);
            }
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Administrador',
                'texto' => $texto,
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/administrador_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/administrador_lista'
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function listaInstituicoesAdmin($usuario_id)
    {

        $sql = "SELECT i.instituicao FROM administrador_instituicao AS ai
                INNER JOIN instituicoes AS i ON ai.instituicao_id = i.id
                WHERE ai.administrador_id = '$usuario_id'";
        return DbModel::consultaSimples($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insereInstituicao()
    {
        $dado['instituicao'] = MainModel::limparString($_POST['instituicao']);

        $insert = DbModel::insert('instituicoes', $dado);
        if ($insert) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Instituição Cadastrada!',
                'texto' => "Instituição <b>{$dado['instituicao']}</b> cadastrada!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/instituicao_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/instituicao_lista'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function editaInstituicao()
    {
        $dado['instituicao'] = MainModel::limparString($_POST['instituicao']);
        $instituicao_id = MainModel::limparString($_POST['instituicao_id']);
        $instituicao_id = MainModel::decryption($instituicao_id);

        $update = DbModel::update('instituicoes', $dado, $instituicao_id);
        if ($update) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Instituição Editada!',
                'texto' => "Instituição <b>{$dado['instituicao']}</b> Editada!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/instituicao_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/instituicao_lista'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function vinculaAdm()
    {

        $instituicao_id = MainModel::decryption($_POST['instituicao_id']);
        $administradores = $_POST['administradores'] ?? false;

        if (!$administradores) {
            $relacionamento = DbModel::deleteEspecial('administrador_instituicao','instituicao_id',$instituicao_id);
        } else {
            $relacionamento = MainModel::atualizaRelacionamento('administrador_instituicao', 'instituicao_id', $instituicao_id, 'administrador_id', $administradores);
        }

        if ($relacionamento) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Administrador(es) vinculados!',
                'texto' => "Administrador(es) viculado(s) a instituição",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/instituicao_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/instituicao_lista'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function insereLocal()
    {
        unset($_POST['_method']);

        $dados = MainModel::limpaPost($_POST);

        $insert = DbModel::insert('locais', $dados);
        if ($insert) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Local Cadastrado!',
                'texto' => "Local <b>{$dados['local']}</b> cadastrado!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/local_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/local_cadastro'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function editaLocal($id)
    {
        $id = MainModel::decryption($id);

        unset($_POST['_method']);
        unset($_POST['id']);

        $dados = MainModel::limpaPost($_POST);

        $update = DbModel::update('locais', $dados, $id);

        if ($update->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Local Editado!',
                'texto' => "Local editado com sucesso!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/local_cadastro&id=' . MainModel::encryption($id)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao editar local!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/local_cadastro&id=' . MainModel::encryption($id)
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function removeLocal($id)
    {
        $id = MainModel::decryption($id);
        $delete = DbModel::apaga('locais', $id);

        if ($delete->rowCount() > 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Local Apagado!',
                'texto' => "Local apagado com sucesso!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/local_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao remover local!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/local_lista'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function listaCategorias()
    {
        return DbModel::listaPublicado('categorias')->fetchAll(PDO::FETCH_OBJ);
    }

    public function insereCategoria()
    {
        unset($_POST['_method']);

        $dado['categoria'] = MainModel::limparString($_POST['categoria']);

        $insert = DbModel::insert('categorias', $dado);
        if ($insert) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Categoria Cadastrada!',
                'texto' => "Categoria <b>{$dado['categoria']}</b> cadastrada!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/categoria_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/categoria_cadastro'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function editaCategoria($id)
    {
        $id = MainModel::decryption($id);

        unset($_POST['_method']);
        unset($_POST['categoria_id']);

        $dado['categoria'] = MainModel::limparString($_POST['categoria']);

        $update = DbModel::update('categorias', $dado, $id);
        if ($update->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Categoria Editada!',
                'texto' => "Categoria editada com sucesso!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/categoria_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao editar local!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/categoria_lista'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }

    public function removeCategoria($id)
    {
        $id = MainModel::decryption($id);
        $delete = DbModel::apaga('categorias', $id);

        if ($delete->rowCount() > 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Categoria Apagada!',
                'texto' => "Categoria apagada com sucesso!",
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/categoria_lista'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao remover local!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/categoria_lista'
            ];
        }

        return MainModel::sweetAlert($alerta);
    }
}