<?php
require_once "./controllers/ChamadoController.php";
require_once "./controllers/NotaController.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;

$chamadoObj = new ChamadoController();
$chamado = $chamadoObj->recuperaChamado($id);
$responsavel = $chamadoObj->recuperaChamadoFuncionario($id);

$notaObj = new NotaController();
$nota = $notaObj->listaNota($id);

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
            <div class="col-12 col-md-6 col-sm-12">
                <!-- Horizontal Form -->
                <div class="card card-outline card-green">
                    <div class="card-header">
                        <h3 class="card-title">Dados</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <p>
                            <b>Status:</b> <?= $chamado->status ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <b>Data abertura:</b> <?= date('d/m/Y', strtotime($chamado->data_abertura)) ?>
                        </p>
                        <p>
                            <b>Categoria:</b> <?= $chamado->categoria ?>
                        </p>
                        <p>
                            <b>Local:</b> <?= $chamado->local ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <b>Telefone:</b> <?= $chamado->telefone ?>
                        </p>

                        <p>
                            <b>Email:</b> <?= $chamado->email ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <b>Contato:</b> <?= $chamado->contato ?>
                        </p>
                        <p>
                            <b>Descrição:</b> <?= $chamado->descricao ?>
                        </p>
                        <?php if ($nota): ?>
                            <p>
                            <b>Notas:</b>
                            <?php foreach ($nota as $notas): ?>
                                <p>
                                    <b><?= date('d/m/Y H:i:s', strtotime($notas->data)) ?></b> <?= $notas->nota ?>
                                </p>
                            <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-12 col-md-6 col-sm-12">
                <div class="card card-outline card-green">
                    <div class="card-header">
                        <h3 class="card-title">Ações</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="formulario-ajax" method="POST"
                          action="<?= SERVERURL ?>ajax/chamadoAjax.php" role="form" data-form="save">
                        <input type="hidden" name="_method" value="atualizarDetalhes">
                        <input type="hidden" name="chamado_id" value="<?= $id ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label for="prioridade_id">Prioridade:</label>
                                    <select class="form-control" name="prioridade_id">
                                        <?php
                                        $chamadoObj->geraOpcao('prioridades', $chamado->prioridade_id)
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="funcionario_id">Responsavel:</label>
                                    <select class="form-control" name="funcionario_id">
                                        <option value="">Selecione um responsavel...</option>
                                        <?php
                                        $chamadoObj->geraOpcao('funcionarios', empty($responsavel) ? "" : $responsavel->funcionario_id, true)
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="ferramentas"> Ferramentas:</label>
                                    <textarea name="ferramentas"
                                              class="form-control"> <?= empty($responsavel) ? "" : $responsavel->ferramentas ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary float-left" type="button" data-toggle="modal"
                                    data-target="#modal-altarar-status"> Alterar Status
                            </button>
                            <button class="btn btn-success float-right" type="submit"> Atualizar</button>
                        </div>
                        <div class="resposta-ajax"></div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="card card-green">
                    <div class="card-header">
                        <h3 class="card-title">Adicionar nota</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="formulario-ajax" method="POST"
                          action="<?= SERVERURL ?>ajax/notaAjax.php" role="form" data-form="save">
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
                                    <textarea name="nota" id="nota" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="resposta-ajax"></div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Gravar</button>
                        </div>
                        <!-- /.card-footer -->
                        <div class="resposta-ajax"></div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="card card-green">
                    <div class="card-header">
                        <h3 class="card-title">Adicionar Solução</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="formulario-ajax" method="POST"
                          action="<?= SERVERURL ?>ajax/chamadoAjax.php" role="form" data-form="save">
                        <input type="hidden" name="_method" value="atualizarSolucao">
                        <input type="hidden" name="chamado_id" value="<?= $chamado->id ?>">
                        <input type="hidden" name="data" value="<?= date('Y-m-d H:i:s') ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md">
                                    <label for="solucao">Solução: *</label>
                                    <textarea name="solucao" id="nota" class="form-control" rows="3"
                                              required><?= $chamado->solucao ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="resposta-ajax"></div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Gravar</button>
                        </div>
                        <!-- /.card-footer -->
                        <div class="resposta-ajax"></div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="modal fade" id="modal-altarar-status">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formulario-ajax" method="POST"
                  action="<?= SERVERURL ?>ajax/chamadoAjax.php" role="form" data-form="save">
                <div class="modal-body">
                    <input type="hidden" name="_method" value="atualizarStatus">
                    <input type="hidden" name="chamado_id" value="<?= $chamado->id ?>">
                    <div class="row">
                        <div class="col">
                            <label for="status_id">Status: *</label>
                            <select name="status_id" class="form-control">
                                <?php
                                $chamadoObj->geraOpcao('chamado_status', $chamado->status_id);
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Alterar</button>
                </div>
                <div class="resposta-ajax"></div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->