<?php
require_once "./models/ViewsModel.php";

class ViewsController extends ViewsModel
{
    private function recuperaViewAtiva(){
        $url = explode("/", $_GET['views']);

        return [
            "view" => $url[1] ?? "",
            "modulo" => $url[0] ?? ""
        ];
    }

    public function exibirTemplate() {
        include "views/template/master.php";
    }

    public function navbar() {
        include "views/template/navbar.php";
    }

    public function sidebar() {
        return "views/template/sidebar.php";
    }

    public function footer() {
        include "views/template/footer.php";
    }

    public function exibirViewController() {
        if (isset($_GET['views'])) {
            $rota = self::recuperaViewAtiva();
            $resposta = ViewsModel::exibirViewModel($rota['view'], $rota['modulo']);
        } else {
            $resposta = "index";
        }
        return $resposta;
    }

    public function exibirMenuController() {
        if (isset($_GET['views'])) {
            $rota = self::recuperaViewAtiva();
            $resposta = ViewsModel::exibirMenuModel($rota['modulo']);
        } else {
            $resposta = "menuPadrao";
        }
        return $resposta;
    }

    public function retornaMenuAtivo() {
        $rota = self::recuperaViewAtiva();
        if ($rota['view'] == "") {
            $ativo = "inicio";
        } else {
            $ativo = $rota['view'];
        }

        return "<script type='application/javascript'>
                        $(document).ready(function () {
                        $('.nav-link').removeClass('active');
                        $('#$ativo').addClass('active');
                    });
                    </script>";
    }
}