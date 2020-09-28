<?php
require_once "./controllers/ChamadoController.php";

$chamadoObj = new ChamadoController();
if (isset($_GET['busca'])){
    $filtros = [];
    foreach ($_GET as $key => $filtro){
        if ($key != 'busca' && $key != 'views' && $filtro != ''){
            $filtros[$key] = $filtro;
        }
    }
    $chamado = $chamadoObj->buscaChamadoAdministrador($filtros);
}
else{
    if ($_SESSION['nivel_acesso_s'] == 2){
        $chamado = $chamadoObj->listaChamadoAdministrador($_SESSION['usuario_id_s'],"1,2");
    }
    else {
        //Corrigir aqui
        $chamado = $chamadoObj->listaChamadoAdministrador($_SESSION['usuario_id_s'],"1,2");
    }
}
?>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.location.reload(1);
        }, 60000); //atualiza a cada 60 segundos
    });
</script>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Lista Chamados</h1>
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
                        <a href="<?= SERVERURL ?>chamado/chamado_cadastro" class="btn btn-success float-right">
                            <i class="fas fa-plus"></i>
                            Adicionar
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabela" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Local</th>
                                <th>Contato</th>
                                <th>Categoria</th>
                                <th>Data abertura</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($chamado AS $chamados):?>
                            <tr>
                                <td><?= $chamados->id ?></td>
                                <td><?= $chamados->local ?></td>
                                <td><?= $chamados->usuario ?></td>
                                <td><?= $chamados->categoria ?></td>
                                <td><?= date('d/m/Y', strtotime($chamados->data_abertura)) ?></td>
                                <td><?= $chamado->tecnico ?? 'não possui' ?></td>
                                <td><?= $chamados->status ?></td>
                                <td>
                                    <a href="nota_cadastro&id=<?= MainModel::encryption($chamados->id) ?>" class="btn btn-sm bg-primary">
                                        <i class="fas fa-folder-open"></i> Carregar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nº</th>
                                <th>Local</th>
                                <th>Contato</th>
                                <th>Categoria</th>
                                <th>Data abertura</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
