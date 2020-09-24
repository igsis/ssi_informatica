<?php
require_once "./controllers/AdministradorController.php";
$administradorObj = new AdministradorController();

$usuarios = $administradorObj->listaUsuarios();
$admins = $administradorObj->listaAdmins();
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
                        <h3 class="card-title">Administradores</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm bg-gradient-info" data-toggle="modal" data-target="#adicionar-adm">
                                <i class="fas fa-plus"></i> Adicionar Administrador
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
                                <th>Instituição(ões) que é Administrador</th>
                                <th width="15%">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $admin):
                                    $instituicoes = $administradorObj->listaInstituicoesAdmin($admin->id)
                                    ?>
                                    <tr>
                                        <td><?=$admin->nome?></td>
                                        <td><?=$admin->email?></td>
                                        <td><?=$admin->telefone?></td>
                                        <td>
                                            <?php
                                            if ($instituicoes) {
                                                echo implode(", ", $instituicoes);
                                            } else {
                                                echo "Nenhuma instituição vinculada";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <form class="formulario-ajax" data-form="save" action="<?= SERVERURL ?>ajax/administradorAjax.php" method="post">
                                                <input type="hidden" name="_method" value="removeAdmin">
                                                <input type="hidden" name="usuario_id" value="<?= $administradorObj->encryption($admin->id) ?>">
                                                <button type="submit" class="form-control btn btn-sm bg-gradient-danger">
                                                    Remover Admin.
                                                </button>
                                                <div class="resposta-ajax"></div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Instituição(ões) que é Administrador</th>
                                <th width="15%">Ações</th>
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
            <form class="formulario-ajax" data-form="save" action="<?= SERVERURL ?>ajax/administradorAjax.php" method="post">
                <input type="hidden" name="_method" value="insereAdmin">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar novo Administrador</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select class="form-control select2bs4" name="usuario_id" id="novoAdm" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $administradorObj->encryption($usuario->id) ?>"><?= $usuario->nome ?></option>
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