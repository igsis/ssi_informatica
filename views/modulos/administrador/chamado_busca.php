<?php
require_once "./controllers/ChamadoController.php";
require_once "./controllers/UsuarioController.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;

$chamadoObj = new ChamadoController();
$usuarioObj = new UsuarioController();

$usuario = $usuarioObj->recuperaUsuario($_SESSION['usuario_id_s'])->fetchObject();
$nivelAcesso = $usuario->nivel_acesso_id;



?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Buscar Chamados</h1>
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
                        <form method="GET" role="form">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 col-md-<?= $nivelAcesso == 2? '6' : '12'?> col-sm-12">
                                                    <div class="form-group">
                                                        <label>Chamado Nº:</label>
                                                        <input type="text" class="form-control" name="nChamado"
                                                               id="nChamado" placeholder="Digite número do chamado">
                                                    </div>
                                                </div>
                                                <?php
                                                    if ($nivelAcesso == 2){
                                                      ?>
                                                        <div class="col-12 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label>Usuário:</label>
                                                                <select class="select2bs4 form-control" style="width: 100%;" id="usuario" name="usuario">
                                                                    <option value="">Selecione um usuário</option>
                                                                    <?php
                                                                    $usuarioObj->geraOpcao("usuarios");
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }else{
                                                        $usuarioId = $usuario->id;
                                                    }
                                                ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-md-8 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Categorias:</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="categoria" name="categoria">
                                                            <option value="">Selecione um categoria</option>
                                                            <?php
                                                            $chamadoObj->geraOpcao("categorias");
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Staus:</label>
                                                        <select class="form-control" style="width: 100%;" id="status">
                                                            <option value="">Selecione um status</option>
                                                            <?php
                                                            $chamadoObj->geraOpcao("chamado_status");
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Descrição:</label>
                                                        <input type="text" name="descricao" id="descricao" class="form-control"
                                                               placeholder="Insira parte do texto da Descrição">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Solução:</label>
                                                        <input type="text" name="solucao" id="solucao" class="form-control"
                                                               placeholder="Insira parte do texto da Solução">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="btnSubmit" class="btn btn-success float-right">
                                    Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
<?php
$url = SERVERURL."administrador/chamado_lista";
$usuario = !empty($usuarioId) ? " ".$usuarioId : " document.querySelector('#usuario').value";
$javascript = '
<script>    
    document.querySelector("#btnSubmit").addEventListener("click",function (event){
        event.preventDefault();
        let nChamado = document.querySelector("#nChamado").value;
        let usuario = '. $usuario .';
        let categoria = document.querySelector("#categoria").value;
        let status = document.querySelector("#status").value;
        let descricao = document.querySelector("#descricao").value;
        let solucao = document.querySelector("#solucao").value;
        
        let url = `'.$url.'&id=${nChamado}&usuario_id=${usuario}&categoria_id=${categoria}&status_id=${status}&descricao=${descricao}&solucao=${solucao}&busca=1`;
        
        window.location.href = url;
    })
</script>
';
?>