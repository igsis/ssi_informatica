<?php
require_once "./controllers/ChamadoController.php";
require_once "./controllers/NotaController.php";
require_once "./controllers/ArquivoController.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;

$chamadoObj = new ChamadoController();
$chamado = $chamadoObj->recuperaChamado($id);

$notaObj = new NotaController();
$nota = $notaObj->listaNota($id, false);

$arquivoObj = new ArquivoController();
$arquivos = $arquivoObj->listarArquivosEnviados($id);
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Chamado nº <?= $chamado->id ?></h1>
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
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Dados</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md">
                                <b>Status:</b> <?= $chamado->status ?? null ?>
                            </div>
                            <div class="col-md">
                                <b>Data abertura:</b> <?= date('d/m/Y', strtotime($chamado->data_abertura)) ?>
                            </div>
                            <div class="col-md">
                                <b>Categoria:</b> <?= $chamado->categoria ?>
                            </div>
                            <div class="col-md">
                                <b>Local:</b> <?= $chamado->local ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <b>Título:</b> <?= $chamado->titulo ?>
                            </div>
                            <div class="col-md">
                                <b>Técnico:</b> <?= $chamado->tecnico ?? "não possui" ?>
                            </div>
                            <?php if ($chamado->status_id == 3): ?>
                                <div class="col-md">
                                    <b>Data encerramento:</b> <?= date('d/m/Y', strtotime($chamado->data_encerramento)) ?>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <b>Descrição:</b> <?= $chamado->descricao ?>
                            </div>
                        </div>
                        <?php if ($arquivos): ?>
                            <div class="row">
                                <div class="col-md">
                                    <b>Arquivos:</b><br/>
                                    <?php foreach ($arquivos as $arquivo): ?>
                                        <b><?= date('d/m/Y H:i:s', strtotime($arquivo->data)) ?>:</b> <a href="<?=SERVERURL?>uploads/<?=$arquivo->arquivo?>" target="_blank"> <?= substr($arquivo->arquivo,15) ?></a><br>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($nota): ?>
                            <div class="row">
                                <div class="col-md">
                                    <b>Notas:</b><br/>
                                    <?php foreach ($nota AS $notas): ?>
                                        <b><?= date('d/m/Y H:i:s', strtotime($notas->data)) . " - " . strstr($notas->nome, ' ', true) ?>:</b> <?= $notas->nota ?><br>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($chamado->status_id == 3): ?>
                            <div class="row">
                                <div class="col-md">
                                    <b>Solução:</b> <?= $chamado->solucao ?>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
        <?php if ($chamado->status_id != 3): ?>
            <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Adicionar nota</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/notaAjax.php" role="form" data-form="save">
                        <input type="hidden" name="_method" value="cadastrar">
                        <input type="hidden" name="pagina" value="chamado">
                        <input type="hidden" name="chamado_id" value="<?= $chamado->id ?>">
                        <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id_s'] ?>">
                        <input type="hidden" name="privada" value="0">
                        <input type="hidden" name="data" value="<?= date('Y-m-d H:i:s') ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md">
                                    <label for="nota">Nota: *</label>
                                    <textarea name="nota" id="nota" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="resposta-ajax"></div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right">Gravar</button>
                        </div>
                        <!-- /.card-footer -->
                        <div class="resposta-ajax"></div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
            <!-- /.row -->
        <?php endif; ?>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->