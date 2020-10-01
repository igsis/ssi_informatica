<?php


class ViewsModel
{
    protected function verificaAcesso($nivel_acesso_id, $modulo)
    {
        if ($nivel_acesso_id == 2 || $nivel_acesso_id == 3) {
            return true;
        } elseif ($modulo == "administrador") {
            return false;
        } else {
            return true;
        }
    }
    protected function verificaModulo ($mod) {
        $modulos = [
            "administrador",
            "chamado",
            "inicio",
        ];

        if (in_array($mod, $modulos)) {
            if (is_dir("./views/modulos/" . $mod)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function exibirViewModel($view, $modulo = "") {
        $whitelist = [
            'inicio',
            'cadastro',
            'chamado_cadastro',
            'chamado_lista',
            'chamado_busca',
            'complemento_oficina_cadastro',
            'demais_anexos',
            'edita',
            'index',
            'login',
            'logout',
            'recupera_senha',
            'resete_senha',
            'chamado_cadastro',
            'nota_cadastro',
            'chamado_lista',
            'chamado_pesquisa',
            'administrador_lista',
            'funcionarios',
            'funcionario_cadastro',
            'categoria_lista',
            'instituicao_lista',
            'local_lista',
            'local_cadastro',
            'categoria_lista',
            'chamado_fechado_lista',
            'tecnico_lista',
            'usuario_lista',
            'usuario_cadastro',
            'usuario_apagado_lista'
        ];
        if (self::verificaModulo($modulo)) {
            $nivel_acesso_id = $_SESSION['nivel_acesso_s'] ?? 1;
            $acesso = self::verificaAcesso($nivel_acesso_id, $modulo);
            if ($acesso) {
                if (in_array($view, $whitelist)) {
                    if (is_file("./views/modulos/$modulo/$view.php")) {
                        $conteudo = "./views/modulos/$modulo/$view.php";
                    } else {
                        $conteudo = "./views/modulos/$modulo/inicio.php";
                    }
                } else {
                    $conteudo = "./views/modulos/$modulo/inicio.php";
                }
            } else {
                $conteudo = "./views/modulos/chamado/inicio.php";
            }
        } elseif ($modulo == "login") {
            $conteudo = "login";
        } elseif ($modulo == "cadastro") {
            $conteudo = "cadastro";
        } elseif ($modulo == "index") {
            $conteudo = "login";
        } elseif ($modulo == "recupera_senha") {
            $conteudo = "recupera_senha";
        } elseif ($modulo == "resete_senha") {
            $conteudo = "resete_senha";
        }
        else {
            $conteudo = "login";
        }

        return $conteudo;
    }

    protected function exibirMenuModel ($modulo) {
        if (self::verificaModulo($modulo)) {
            $nivel_acesso_id = $_SESSION['nivel_acesso_s'] ?? 1;

            switch ($nivel_acesso_id){
                case 1:
                    $menu = "./views/modulos/chamado/include/menu.php";
                    break;
                case 2:
                case 3:
                    $menu = "./views/modulos/administrador/include/menu.php";
                    break;
                default:
                    $menu = "./views/template/menuExemplo.php";
                    break;
            }
        } else {
            $menu = "./views/template/menuExemplo.php";
        }

        return $menu;
    }
}