<?php
require_once "./controllers/UsuarioController.php";
$usuarioObj = new UsuarioController();

$tecnicos = $usuarioObj->listaUsuarios("3");
$usuarios = $usuarioObj->listaUsuarios("1,2");
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"></h1>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Técnicos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm bg-gradient-info" data-toggle="modal" data-target="#adicionar-adm">
                                <i class="fas fa-plus"></i> Adicionar Técnico
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabela" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Instituição</th>
                                <th>Local</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tecnicos as $user):
                                    ?>
                                    <tr>
                                        <td><?=$user->nome?></td>
                                        <td><?=$user->email1?></td>
                                        <td><?=$user->telefone?></td>
                                        <td><?=$user->instituicao?></td>
                                        <td><?=$user->local?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-td">
                                                    <form class="form-horizontal formulario-ajax" method="POST" action="<?=SERVERURL?>ajax/usuarioAjax.php" role="form" data-form="update">
                                                        <input type="hidden" name="_method" value="editaUsuario">
                                                        <input type="hidden" name="pagina" value="administrador/tecnico_lista">
                                                        <input type="hidden" name="nivel_acesso_id" value="1">
                                                        <input type="hidden" name="id" value="<?= $user->id ?>">
                                                        <button type="submit" class="btn btn-sm bg-gradient-danger">
                                                            <i class="fas fa-trash"></i> Remover Técnico
                                                        </button>
                                                        <div class="resposta-ajax"></div>
                                                    </form>
                                                </div>
                                                <div class="col-md" data-toggle="tooltip" data-placement="top" title="Senha padrão: ssi2020">
                                                    <form class="form-horizontal formulario-ajax" method="POST" action="<?=SERVERURL?>ajax/usuarioAjax.php" role="form" data-form="update">
                                                        <input type="hidden" name="_method" value="trocaSenhaUsuario">
                                                        <input type="hidden" name="pagina" value="administrador/tecnico_lista">
                                                        <input type="hidden" name="id" value="<?= $user->id ?>">
                                                        <input type="hidden" name="senha" value="ssi2020">
                                                        <input type="hidden" name="senha2" value="ssi2020">
                                                        <button type="submit" class="btn btn-block btn-sm bg-gradient-warning">
                                                            <i class="fas fa-sync"></i> Senha
                                                        </button>
                                                        <div class="resposta-ajax"></div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Instituição</th>
                                <th>Local</th>
                                <th>Ações</th>
                            </tr>
                            </tfoot>
                        </table>
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
<div class="modal fade" id="adicionar-adm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal formulario-ajax" method="POST" action="<?=SERVERURL?>ajax/usuarioAjax.php" role="form" data-form="update">
                <input type="hidden" name="_method" value="editaUsuario">
                <input type="hidden" name="pagina" value="administrador/tecnico_lista">
                <input type="hidden" name="nivel_acesso_id" value="3">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar técnico</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select class="form-control select2bs4" name="id" id="id" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario->id ?>"><?= $usuario->nome ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Adicionar</button>
                </div>
                <div class="resposta-ajax"></div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->