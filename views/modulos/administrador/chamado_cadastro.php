<?php
require_once "./controllers/UsuarioController.php";
require_once "./controllers/LocalController.php";
require_once "./controllers/AdministradorController.php";

$id = !empty($_GET['id']) ? $_GET['id'] : false;

$usuarioObj = new UsuarioController();
$usuario = $usuarioObj->recuperaUsuario($_SESSION['usuario_id_s'])->fetchObject();

$localObj = new LocalController();
$admin = $localObj->recuperaAdministrador('', $usuario->local_id)->fetchObject();

$admObj = new AdministradorController();
$categorias = $admObj->listaCategorias();
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastro de chamado</h1>
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
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Dados</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/chamadoAjax.php"
                          role="form" data-form="<?= ($id) ? "update" : "save" ?>">
                        <input type="hidden" name="_method" value="<?= ($id) ? "editar" : "cadastrar" ?>">
                        <input type="hidden" name="pagina" value="administrador">
                        <input type="hidden" name="administrador_id" value="<?= $admin->administrador_id ?>">
                        <input type="hidden" name="prioridade_id" value="1">
                        <input type="hidden" name="status_id" value="1">
                        <?php if (!$id): ?>
                            <input type="hidden" name="data_abertura" value="<?= date('Y-m-d H:i:s') ?>">
                        <?php endif; ?>
                        <?php if ($id): ?>
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md">
                                    <label for="usuario_id">Usuário: *</label>
                                    <select name="usuario_id" id="usuario_id" class="form-control" required>
                                        <option>Selecione uma opção</option>
                                        <?php $usuarioObj->geraOpcao("usuarios",""); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="form-group col-md">
                                            <label for="titulo">Título: *</label>
                                            <input type="text" id="titulo" name="titulo" class="form-control" maxlength="60" placeholder="Digite o título do chamado" value="<?= $usuario->titulo ?? '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md">
                                            <label for="categoria">Categorias: *
                                                <button class="btn btn-sm btn-default rounded-circle" type="button" data-toggle="modal" data-target="#infoCategorias">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="categoria" name="categoria_id">
                                                <option value="">Selecione uma opção...</option>
                                                <?php $usuarioObj->geraOpcao("categorias", "", true); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="descricao">Descrição: *</label>
                                            <textarea name="descricao" id="descricao" class="form-control" rows="5" required></textarea>
                                        </div>
                                    </div>
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
<!-- /.content -->
<!-- Modal -->
<div class="modal fade" id="infoCategorias" tabindex="-1" role="dialog" aria-labelledby="infoCategorias"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalTitulo">Categorias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ($categorias as $categoria): ?>
                    <h5><?= $categoria->categoria ?></h5>
                    <p><?= nl2br($categoria->descricao) ?></p>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>