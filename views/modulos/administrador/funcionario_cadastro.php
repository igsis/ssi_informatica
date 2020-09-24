<?php
require_once "./controllers/FuncionarioController.php";

$id = !empty($_GET['id']) ? $_GET['id'] : false;

if ($id){
    $funcionarioObj = new FuncionarioController();
    $funcionario = $funcionarioObj->recuperaFuncionario($id);
}

?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastro de Funcionário</h1>
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
                    <form class="formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/funcionarioAjax.php" role="form" data-form="<?= ($id) ? "update" : "save" ?>">
                        <input type="hidden" name="_method" value="<?= ($id) ? "editar" : "cadastrar" ?>">
                        <?php if ($id): ?>
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 col-sm-12">
                                    <label for="nome">Nome Completo: *</label>
                                    <input type="text" class="form-control" maxlength="120" name="nome" value="<?= isset($funcionario) ? $funcionario->nome : '' ?>">
                                </div>
                                <div class="col-12 col-md-6 col-sm-12">
                                    <label for="nome">Cargo: *</label>
                                    <input type="text" class="form-control" maxlength="45" name="cargo" value="<?= isset($funcionario) ? $funcionario->cargo : '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="resposta-ajax"></div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Gravar</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->