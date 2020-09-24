<?php
$template = new ViewsController();

$pedidoAjax = false;
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>SSI | SMC</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= SERVERURL ?>views/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= SERVERURL ?>views/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= SERVERURL ?>views/dist/css/custom.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= SERVERURL ?>views/plugins/datatables/dataTables.bootstrap4.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?= SERVERURL ?>views/plugins/daterangepicker/daterangepicker.css">
    <!-- Sweet Alert 2 -->
    <script src="<?= SERVERURL ?>views/plugins/sweetalert2/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?= SERVERURL ?>views/plugins/sweetalert2/sweetalert2.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- jQuery -->
    <script src="<?= SERVERURL ?>views/plugins/jquery/jquery.min.js"></script>
    <link rel="shortcut icon" href="<?= SERVERURL ?>views/dist/img/logo_ssi.png" />
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= SERVERURL ?>views/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= SERVERURL ?>views/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        a.btn, a.btn:hover{
            color: #fff;
        }
    </style>
</head>
<!--<body class="hold-transition login-page">-->
<body class="hold-transition sidebar-mini accent-success">
<?php

session_start(['name' => 'ssi']);
$view = $template->exibirViewController();
if ($view == 'index'):
    require_once "./views/modulos/inicio/index.php";
elseif ($view == 'cadastro'):
    require_once "./views/modulos/inicio/cadastro.php";
elseif ($view == 'recupera_senha'):
    require_once "./views/modulos/inicio/recupera_senha.php";
elseif($view == 'resete_senha'):
    require_once "./views/modulos/inicio/resete_senha.php";
else:
    require_once "./controllers/UsuarioController.php";
    $usuario = new UsuarioController();

    if (!isset($_SESSION['usuario_id_s'])) {
        $usuario->forcarFimSessao();
    }
    ?>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-success">
            <?php $template->navbar(); ?>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include $view ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-olive elevation-4">
            <?php include $template->sidebar(); ?>
        </aside>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Sobre</h5>
                <p>Versão 2.0</p>
                <br>
                <p>Desenvolvido por:</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <?php $template->footer() ?>
        </footer>
    </div>
    <!-- ./wrapper -->
<?php endif; ?>
<!-- REQUIRED SCRIPTS -->
<?php if(isset($sectionJS))
        echo $sectionJS;
?>
<script src="<?= SERVERURL ?>views/plugins/moment/moment.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= SERVERURL ?>views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= SERVERURL ?>views/dist/js/adminlte.min.js"></script>
<script src="<?= SERVERURL ?>views/plugins/jquery-mask/jquery.mask.js"></script>
<!-- DataTables -->
<script src="<?= SERVERURL ?>views/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= SERVERURL ?>views/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Outros Scripts -->
<script src="<?= SERVERURL ?>views/dist/js/main.js"></script>
<!-- date-range-picker -->
<script src="<?= SERVERURL ?>views/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Select2 -->
<script src="<?= SERVERURL ?>views/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= SERVERURL ?>views/plugins/select2/js/i18n/pt-BR.js" type="text/javascript"></script>

<script>
    $(document).ready(function (){
        //Initialize Select2 Elements
        $('.select2').select2();

        $('.select2bs4').select2({
            theme: 'bootstrap4',
            language: 'pt-BR'
        });
    });
</script>
<?= (isset($javascript)) ? $javascript : ''; ?>
</body>
</html>
