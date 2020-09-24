<?php
require_once "./controllers/LocalController.php";
$localObj = new LocalController();

$locais = $localObj->listaLocais();
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
                        <h3 class="card-title">Locais</h3>
                        <div class="card-tools">
                            <a href="<?=SERVERURL?>administrador/local_cadastro" class="btn btn-sm bg-gradient-info">
                                <i class="fas fa-plus"></i> Adicionar Local
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabela" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Local</th>
                                    <th>Instituição</th>
                                    <th>Telefone</th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($locais as $local): ?>
                                    <tr>
                                        <td><?=$local->local?></td>
                                        <td><?=$localObj->recuperaInstituicaoLocal($local->id)?></td>
                                        <td><?=$local->telefone?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-6">
                                                    <a href="<?=SERVERURL?>administrador/local_cadastro&id=<?= $localObj->encryption($local->id) ?>"
                                                       class="btn bg-gradient-primary float-left">
                                                        Editar
                                                    </a>
                                                </div>
                                                <div class="col-6">
                                                    <form class="formulario-ajax" data-form="delete" action="<?= SERVERURL ?>ajax/administradorAjax.php" method="post">
                                                        <input type="hidden" name="_method" value="removeLocal">
                                                        <input type="hidden" name="local_id" value="<?= $localObj->encryption($local->id) ?>">
                                                        <button type="submit" class="btn bg-gradient-danger float-left">
                                                            Remover
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
                                    <th>Local</th>
                                    <th>Instituição</th>
                                    <th>Telefone</th>
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