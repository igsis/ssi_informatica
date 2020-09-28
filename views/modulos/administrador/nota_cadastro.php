<?php
require_once "./controllers/ChamadoController.php";
require_once "./controllers/NotaController.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;

$chamadoObj = new ChamadoController();
$chamado = $chamadoObj->recuperaChamado($id);
$tecnicos = $chamadoObj->listaTecnicoUnidade();

$notaObj = new NotaController();
$nota = $notaObj->listaNota($id);

if ($chamado->status_id == 3){
    $disabled = "disabled";
} else{
    $disabled = "";
}
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
            <div class="col-md-9">
                <!-- Horizontal Form -->
                <div class="card card-outline card-green">
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
                                <?= $chamado->data_encerramento ? "<b>Data fechamento:</b> ".date('d/m/Y', strtotime($chamado->data_encerramento)) : null ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <b>Categoria:</b> <?= $chamado->categoria ?>
                            </div>
                            <div class="col-md">
                                <b>Local:</b> <?= $chamado->local ?>
                            </div>
                            <div class="col-md">
                                <b>Técnico:</b> <?= $chamado->tecnico ?? 'não possui' ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <b>Contato:</b> <?= $chamado->usuario ?>
                            </div>
                            <div class="col-md">
                                <b>Telefone:</b> <?= $chamado->telefone ?>
                            </div>
                            <div class="col-md">
                                <b>Email:</b> <?= $chamado->email1 ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <b>Descrição:</b> <?= $chamado->descricao ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md">
                        <div class="card card-outline card-green">
                            <div class="card-header"><h3 class="card-title">Alterar status para:</h3></div>
                            <!-- form start -->
                            <div class="card-body">
                                <div class="row">
                                    <?php if ($chamado->status_id != 3): ?>
                                        <?php if ($chamado->status_id == 1): ?>
                                            <div class="col-md">
                                                <form class="form-horizontal formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/chamadoAjax.php" role="form" data-form="update">
                                                    <input type="hidden" name="_method" value="editar">
                                                    <input type="hidden" name="id" value="<?= $chamadoObj->encryption($chamado->id) ?>">
                                                    <input type="hidden" name="data_progresso" value="<?= date('Y-m-d H:i:s') ?>">
                                                    <input type="hidden" name="status_id" value="2">
                                                    <input type="hidden" name="tecnico_id" value="<?= $_SESSION['usuario_id_s'] ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Em andamento</button>
                                                    <div class="resposta-ajax"></div>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-md">
                                                <button class="btn btn-outline-primary btn-sm btn-block" disabled>Andamento em <?= date("d/m/Y", strtotime($chamado->data_progresso)) ?></button>
                                            </div>
                                        <?php endif;?>
                                        <div class="col-md">
                                            <button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#alterarStatus">
                                                Fechado
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-md">
                                            <form class="form-horizontal formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/chamadoAjax.php" role="form" data-form="update">
                                                <input type="hidden" name="_method" value="editar">
                                                <input type="hidden" name="id" value="<?= $chamadoObj->encryption($chamado->id) ?>">
                                                <input type="hidden" name="data_encerramento" value="NULL">
                                                <input type="hidden" name="status_id" value="2">
                                                <button type="submit" class="btn btn-primary btn-sm btn-block">Reabrir</button>
                                                <div class="resposta-ajax"></div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($chamado->status_id == 2): ?>
                    <div class="row">
                        <div class="col-md">
                            <div class="card card-outline card-green">
                                <div class="card-header"><h3 class="card-title">Trocar de técnico:</h3></div>
                                <!-- form start -->
                                <div class="card-body">
                                    <form class="form-horizontal formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/chamadoAjax.php" role="form" data-form="update">
                                        <input type="hidden" name="_method" value="editar">
                                        <input type="hidden" name="id" value="<?= $chamadoObj->encryption($chamado->id) ?>">
                                        <div class="row">
                                            <div class="col-m">
                                                <select class="form-control" name="tecnico_id" id="tecnico_id">
                                                    <option value="">Selecione uma opção...</option>
                                                    <?php foreach ($tecnicos as $tecnico): ?>
                                                        <option value="<?= $tecnico->id ?>"><?= $tecnico->nome ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success">Gravar</button>
                                            </div>
                                        </div>
                                        <div class="resposta-ajax"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md">
                <!-- Horizontal Form -->
                <div class="card card-outline card-green">
                    <div class="card-header">
                        <h3 class="card-title">Notas</h3>
                        <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#modal-notas" <?=$disabled?>>
                            <i class="fas fa-plus"></i> Adicionar
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md">
                                <?php foreach ($nota as $notas): ?>
                                    <b><?= date('d/m/Y H:i:s', strtotime($notas->data)) . " - " . strstr($notas->nome, ' ', true) ?><?php if ($notas->privada == 1) echo "(Privada)" ?>:</b> <?= $notas->nota ?><br>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <?php if ($chamado->status_id == 3) :?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="card card-outline card-green">
                        <div class="card-header">
                            <h3 class="card-title">Solução</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <?= $chamado->solucao ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        <?php endif; ?>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- modal notas -->
<div class="modal fade" id="modal-notas" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/notaAjax.php"
              role="form" data-form="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar nota</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" value="cadastrar">
                    <input type="hidden" name="pagina" value="administrador">
                    <input type="hidden" name="chamado_id" value="<?= $chamado->id ?>">
                    <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id_s'] ?>">
                    <input type="hidden" name="privada" value="0">
                    <input type="hidden" name="data" value="<?= date('Y-m-d H:i:s') ?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md">
                                <label for="nota">Nota: *</label>
                                <textarea name="nota" id="nota" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="row float-right">
                            <div class="form-group col-md">
                                <input class='form-check-input' type='checkbox' name='privada' id="privada" value="1">
                                <label class='form-check-label' for="privada">Privada</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Gravar</button>
                </div>
            </div>
            <div class="resposta-ajax"></div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal notas -->


<!-- Modal -->
<div class="modal fade" id="alterarStatus" tabindex="-1" role="dialog" aria-labelledby="statusModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fechar o chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formulario-ajax" action="<?= SERVERURL ?>ajax/chamadoAjax.php" method="post">
                <input type="hidden" name="_method" value="editar">
                <input type="hidden" name="id" value="<?= $chamadoObj->encryption($chamado->id) ?>">
                <input type="hidden" name="data_encerramento" value="<?= date('Y-m-d H:-i:s') ?>">
                <input type="hidden" name="status_id" value="3">
                <div class="modal-body">
                    <p>Para encerrar um chamado é necessário inserir a solução.</p>
                    <label for="solucao">Solução: *</label>
                    <textarea name="solucao" id="solucao" class="form-control" rows="5" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Gravar</button>
                </div>
                <div class="resposta-ajax"></div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal Status -->