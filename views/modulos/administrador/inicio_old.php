<?php

require_once "./controllers/ChamadoController.php";

$chamadoObj = new  ChamadoController();

$estatistica = $chamadoObj->recuperaEstatisticaAdm();

?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Chamados</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="card card-info">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3><?= $estatistica[0] ?></h3>
                                        <p>Abertos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fab fa-wpforms"></i>
                                    </div>
                                    <a href="<?= SERVERURL ?>/chamado" class="small-box-footer">Mais <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3><?= $estatistica[1] ?></h3>
                                        <p>Em andamento</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fab fa-wpforms"></i>
                                    </div>
                                    <a href="<?= SERVERURL ?>chamado" class="small-box-footer">Mais <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3><?= $estatistica[2] ?></h3>
                                        <p>Fechados</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                    <a href="<?= SERVERURL ?>chamado" class="small-box-footer">Mais <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<script type="application/javascript">
    $(document).ready(function () {
        $('.nav-link').removeClass('active');
        $('#chamado_inicio').addClass('active');
    })
</script>