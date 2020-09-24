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

            @session_start(['name' => 'ssi']);
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
        $camposIgnorados = ["senha2", "_method", "rf_rg"];
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
        $consultaEmail = DbModel::consultaSimples("SELECT email FROM usuarios WHERE email = '{$dados['email']}'");
        if ($consultaEmail->rowCount() >= 1) {
            $erro = true;
            $alerta = [
                'alerta' => 'simples',
                'titulo' => "Erro!",
                'texto' => "Email inserido já cadastrado. Tente novamente.",
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
                    'location' => SERVERURL
                ];
            }
        }
        return MainModel::sweetAlert($alerta);
    }

    /* edita */
    public function editaUsuario($dados, $id){
        unset($dados['_method']);
        unset($dados['id']);
        $dados = MainModel::limpaPost($dados);
        $edita = DbModel::update('usuarios', $dados, $id);
        if ($edita) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Usuário',
                'texto' => 'Informações alteradas com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL.'inicio/edita'
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL.'inicio/edita'
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
                    'location' => SERVERURL.'inicio/edita'
                ];
            }
            else{
                $alerta = [
                    'alerta' => 'simples',
                    'titulo' => 'Erro!',
                    'texto' => 'Erro ao salvar!',
                    'tipo' => 'error',
                    'location' => SERVERURL.'inicio/edita'
                ];
            }
        }
        return MainModel::sweetAlert($alerta);
    }

    public function recuperaUsuario($id) {
        $usuario = DbModel::getInfo('usuarios',$id);
        return $usuario;
    }

    public function recuperaEmail($email){
        return UsuarioModel::getExisteEmail($email);;
    }

    public function listaUsuarios()
    {
        return DbModel::consultaSimples("SELECT * FROM usuarios WHERE publicado = 1 AND nivel_acesso_id = 1")->fetchAll(PDO::FETCH_OBJ);
    }
}
