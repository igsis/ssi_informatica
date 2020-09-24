<?php

require_once "./controllers/FuncionarioController.php";

$funcionarioObj = new FuncionarioController();

$funcionarios = $funcionarioObj->listarFuncionario();

?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Funcionários</h1>
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
                        <h3 class="card-title">Lista de Funcionário</h3>
                        <a href="<?= SERVERURL ?>administrador/funcionario_cadastro"
                           class="btn btn-success float-right">
                            <i class="fas fa-plus"></i>
                            Adicionar
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table id="tabela" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nome</th>
                                        <th>Cargo</th>
                                        <th width="20%">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($funcionarios as $funcionario) {
                                        ?>
                                        <tr>
                                            <td><?= $funcionario->id ?></td>
                                            <td><?= $funcionario->nome ?></td>
                                            <td><?= $funcionario->cargo ?></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a href="<?= SERVERURL ?>administrador/funcionario_cadastro&id=<?= MainModel::encryption($funcionario->id) ?>"
                                                           class="btn btn-primary">Editar</a>
                                                    </div>
                                                    <div class="col-6">
                                                        <form class="formulario-ajax" method="POST"
                                                              action="<?= SERVERURL ?>ajax/funcionarioAjax.php" role="form">
                                                            <input type="hidden" name="id" value="<?= MainModel::encryption($funcionario->id) ?>">
                                                            <input type="hidden" name="_method" value="remover">
                                                            <button type="submit" class="btn btn-danger">Remover</button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nome</th>
                                        <th>Cargo</th>
                                        <th>Ações</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="resposta-ajax"></div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->