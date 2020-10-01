<?php

if ($pedidoAjax) {
    require_once "../models/UsuarioModel.php";
} else {
    require_once "./models/UsuarioModel.php";
}


class UsuarioController extends UsuarioModel
{

    public function iniciaSessao($modulo = false, $edital = null) {
        $email = MainModel::limparString($_POST['usuario']);
        $senha = MainModel::limparString($_POST['senha']);
        $senha = MainModel::encryption($senha);

        $dadosLogin = [
            'usuario' => $email,
            'senha' => $senha
        ];

        $consultaUsuario = UsuarioModel::getUsuario($dadosLogin);

        if ($consultaUsuario->rowCount() == 1) {
            $usuario = $consultaUsuario->fetch();

            @session_start(['name' => 'ssi_informatica']);
            $_SESSION['login_s'] = $usuario['usuario'];
            $_SESSION['usuario_id_s'] = $usuario['id'];
            $_SESSION['nome_s'] = $usuario['nome'];
            $_SESSION['nivel_acesso_s'] = $usuario['nivel_acesso_id'];

            MainModel::gravarLog('Fez Login');

            if ($usuario['nivel_acesso_id'] == 1) {
                return $urlLocation = "<script> window.location='chamado/inicio' </script>";
            } else {
                return $urlLocation = "<script> window.location='administrador/inicio' </script>";
            }

        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Usuário / Senha incorreto',
                'tipo' => 'error'
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function forcarFimSessao() {
        session_destroy();
        return header("Location: ".SERVERURL);
    }

    public function insereUsuario() {
        $erro = false;
        $dados = [];
        $pagina = isset($_POST['pagina']) ? SERVERURL.$_POST['pagina'] : SERVERURL;

        $dados['email1'] = $_POST['email1']."@prefeitura.sp.gov.br";
        $camposIgnorados = ["_method", "pagina", "senha2", "jovem_monitor", "rf_rg", "email1"];
        foreach ($_POST as $campo => $post) {
            if (!in_array($campo, $camposIgnorados)) {
                $dados[$campo] = MainModel::limparString($post);
            }
        }

        // Valida Senha
        if ($_POST['senha'] != $_POST['senha2']) {
            $erro = true;
            $alerta = [
                'alerta' => 'simples',
                'titulo' => "Erro!",
                'texto' => "As senhas inseridas não conferem. Tente novamente",
                'tipo' => "error"
            ];
        }

        // Valida email unique
        $consultaEmail = DbModel::consultaSimples("SELECT email1 FROM usuarios WHERE email1 = '{$dados['email1']}'");
        if ($consultaEmail->rowCount() >= 1) {
            $erro = true;
            $alerta = [
                'alerta' => 'simples',
                'titulo' => "Erro!",
                'texto' => "Email#1 informado já foi cadastrado. Tente novamente.",
                'tipo' => "error"
            ];
        }

        if (!$erro) {
            $dados['senha'] = MainModel::encryption($dados['senha']);
            $insere = DbModel::insert('usuarios', $dados);
            if ($insere) {
                $alerta = [
                    'alerta' => 'sucesso',
                    'titulo' => 'Usuário Cadastrado!',
                    'texto' => "Usuário cadastrado com Sucesso! Seu usuário é <b>{$dados['usuario']}</b>",
                    'tipo' => 'success',
                    'location' => $pagina
                ];
            }
        }
        return MainModel::sweetAlert($alerta);
    }
    
    public function editaUsuario($dados, $id){
        $camposIgnorados = ["_method", "pagina", "jovem_monitor", "rf_rg", "id"];
        foreach ($camposIgnorados as $campo) {
            unset($dados[$campo]);
        }

        $pagina =  $_POST['pagina'];
        if ($pagina == "administrador/usuario_lista"){
            $id = MainModel::decryption($id);
            $dados['email1'] = "{$dados['email1']}@prefeitura.sp.gov.br";
        }

        $dados = MainModel::limpaPost($dados);
        $edita = DbModel::update('usuarios', $dados, $id);
        if ($edita) {

            if ($pagina == "administrador/tecnico_lista") {
                if ($dados['nivel_acesso_id'] == 3) {
                    if (!parent::getInstituicaoTecnico($id)) {
                        parent::insereTecnicoInstituicao($id);
                    }
                } elseif ($dados['nivel_acesso_id'] == 1){
                    if (parent::getInstituicaoTecnico($id)) {
                        DbModel::deleteEspecial('tecnico_instituicao', 'tecnico_id', $id);
                    }
                }
            }

            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Usuário',
                'texto' => 'Informações alteradas com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL. $pagina.'&id='.MainModel::encryption($id)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL.$pagina.'&id='.MainModel::encryption($id)
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function apagaUsuario()
    {
        $pagina =  $_POST['pagina'];
        $apaga = DbModel::apaga("usuarios",$_POST['id']);
        if ($apaga) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Usuário',
                'texto' => 'Usuário removido com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL. $pagina
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao remover usuário. Tente novamente!',
                'tipo' => 'error',
                'location' => SERVERURL.$pagina
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function trocaSenha($dados,$id){
        // Valida Senha
        if ($_POST['senha'] != $_POST['senha2']) {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => "Erro!",
                'texto' => "As senhas inseridas não conferem. Tente novamente",
                'tipo' => "error"
            ];
        }
        else{
            $pagina = $_POST['pagina'];
            unset($dados['pagina']);
            unset($dados['_method']);
            unset($dados['id']);
            unset($dados['senha2']);
            $dados = MainModel::limpaPost($dados);
            $dados['senha'] = MainModel::encryption($dados['senha']);
            $edita = DbModel::update('usuarios', $dados, $id);
            if ($edita) {
                $alerta = [
                    'alerta' => 'sucesso',
                    'titulo' => 'Usuário',
                    'texto' => 'Senha alterada com sucesso!',
                    'tipo' => 'success',
                    'location' => SERVERURL.$pagina
                ];
            }
            else{
                $alerta = [
                    'alerta' => 'simples',
                    'titulo' => 'Erro!',
                    'texto' => 'Erro ao salvar!',
                    'tipo' => 'error',
                    'location' => SERVERURL.$pagina
                ];
            }
        }
        return MainModel::sweetAlert($alerta);
    }

    public function recuperaUsuario($id) {
        $tipo = strlen($id);
        if ($tipo > 10){
            $id = MainModel::decryption($id);
        }
        return DbModel::getInfo('usuarios',$id);
    }

    public function recuperaEmail($email){
        return UsuarioModel::getExisteEmail($email);
    }

    public function listaUsuarios($nivel_acesso)
    {
        $sql = "SELECT u.*, i.instituicao, l.local FROM usuarios u INNER JOIN instituicoes i on u.instituicao_id = i.id INNER JOIN locais l on u.local_id = l.id WHERE u.publicado = 1 AND nivel_acesso_id IN ($nivel_acesso)";
        return DbModel::consultaSimples($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    public function listaInstituicoesTecnicos($usuario_id)
    {

        $sql = "SELECT i.instituicao FROM tecnico_instituicao AS ti
                INNER JOIN instituicoes AS i ON ti.instituicao_id = i.id
                WHERE ti.tecnico_id = '$usuario_id'";
        return DbModel::consultaSimples($sql)->fetchAll(PDO::FETCH_COLUMN);
    }
}
