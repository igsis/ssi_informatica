<?php

require_once "./controllers/ChamadoController.php";

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$viewObj = new ViewsController();

$mes = strftime('%B', strtotime('today'));

$chamadoObj = new ChamadoController();

$relatorio = $chamadoObj->relatorioChamadosMes();
$categorias = $chamadoObj->listaCategorias();
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Bem-vindo ao SSI</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">SSI</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <div class="widget-user-header">
                                    <h3 class="widget-user-username">Estatistica</h3>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Categorias</th>
                                        <th>Abertos</th>
                                        <th>Fechados</th>
                                        <th>Em Andamento</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($categorias as $categoria):
                                        $estatistica = $chamadoObj->recuperaEstatisticaCategoria($categoria->id);
                                        ?>
                                        <tr>
                                            <th><?= $categoria->categoria ?></th>
                                            <td><?= $estatistica[1] ?></td>
                                            <td><?= $estatistica[2] ?></td>
                                            <td><?= $estatistica[3] ?></td>
                                            <td><?= $estatistica[0] ?></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12">
                        <div class="card card-widget widget-user-2 shadow-sm">
                            <div class="widget-user-header bg-warning">
                                <h3 class="widget-user-username">Relatório de <?= $mes ?></h3>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                    <?php
                                    foreach ($relatorio as $rel) {
                                        ?>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <?= $rel->status ?> <span
                                                        class="float-right badge bg-primary"><?= $rel->quantidade ?></span>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->