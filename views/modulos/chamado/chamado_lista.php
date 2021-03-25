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
    $chamado = $chamadoObj->listaChamadoUsuario($_SESSION['usuario_id_s']);
}
?>

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
                                <th>Categoria</th>
                                <th>Título</th>
                                <th>Descrição</th>
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
                                <td><?= $chamados->categoria ?></td>
                                <td><?= $chamados->titulo ?></td>
                                <td><?= mb_strimwidth($chamados->descricao,'0', '25', '...') ?></td>
                                <td><?= date('d/m/Y', strtotime($chamados->data_abertura)) ?></td>
                                <td><?= $chamados->tecnico ?? "não possui" ?></td>
                                <td><?= $chamados->status ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <a href="nota_cadastro&id=<?= MainModel::encryption($chamados->id) ?>" class="btn btn-sm bg-primary">
                                                <i class="fas fa-folder-open"></i> Carregar
                                            </a>
                                        </div>
                                        <?php if ($chamados->status == "Fechado"){ ?>
                                            <div class="col">
                                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdXoeb9tgc2R7C1QSq0QIA1qFsm1XYTAVAPP_EYeJ74gKU3yA/viewform?usp=pp_url&entry.1872662039=<?= $chamados->id ?>&entry.1075408560=<?=$_SESSION['nome_s']?>&entry.683680955=<?=$chamados->local?>&entry.1854038216=<?=$_SESSION['login_s']?>" class="btn btn-sm bg-secondary" target="_blank"><i class="fas fa-info-circle"></i> Pesquisa de opinião</a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nº</th>
                                <th>Categoria</th>
                                <th>Título</th>
                                <th>Descrição</th>
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
