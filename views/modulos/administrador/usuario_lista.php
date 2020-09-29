<?php
require_once "./controllers/UsuarioController.php";
$usuarioObj = new UsuarioController();

$usuarios = $usuarioObj->listaUsuarios("1");
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
                        <h3 class="card-title">Usuários</h3>
                        <div class="card-tools">
                            <a href="<?=SERVERURL?>administrador/usuario_cadastro" class="btn btn-sm bg-gradient-info">
                                <i class="fas fa-plus"></i> Adicionar Usuário
                            </a>
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
                                <?php foreach ($usuarios as $user):
                                    ?>
                                    <tr>
                                        <td><?=$user->nome?></td>
                                        <td><?=$user->email1?></td>
                                        <td><?=$user->telefone?></td>
                                        <td><?=$user->instituicao?></td>
                                        <td><?=$user->local?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md">
                                                    <a href="<?=SERVERURL?>administrador/usuario_cadastro&id=<?= $usuarioObj->encryption($user->id) ?>"
                                                       class="btn btn-sm btn-block bg-gradient-primary">
                                                        <i class="far fa-edit"></i> Editar
                                                    </a>
                                                </div>
                                                <div class="col-md" data-toggle="tooltip" data-placement="top" title="Senha padrão: ssi2020">
                                                    <form class="form-horizontal formulario-ajax" method="POST" action="<?=SERVERURL?>ajax/usuarioAjax.php" role="form" data-form="update">
                                                        <input type="hidden" name="_method" value="trocaSenhaUsuario">
                                                        <input type="hidden" name="pagina" value="administrador/usuario_lista">
                                                        <input type="hidden" name="id" value="<?= $user->id ?>">
                                                        <input type="hidden" name="senha" value="ssi2020">
                                                        <input type="hidden" name="senha2" value="ssi2020">
                                                        <button type="submit" class="btn btn-block btn-sm bg-gradient-warning">
                                                            <i class="fas fa-sync"></i> Senha
                                                        </button>
                                                        <div class="resposta-ajax"></div>
                                                    </form>
                                                </div>
                                                <div class="col-md">
                                                    <form class="form-horizontal formulario-ajax" method="POST" action="<?=SERVERURL?>ajax/usuarioAjax.php" role="form" data-form="update">
                                                        <input type="hidden" name="_method" value="apagaUsuario">
                                                        <input type="hidden" name="pagina" value="administrador/usuario_lista">
                                                        <input type="hidden" name="id" value="<?= $user->id ?>">
                                                        <button type="submit" class="btn btn-block btn-sm bg-gradient-danger">
                                                            <i class="fas fa-trash"></i> Remover
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
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>