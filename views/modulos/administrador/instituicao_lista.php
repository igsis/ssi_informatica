<?php
require_once "./controllers/InstituicaoController.php";
require_once "./controllers/AdministradorController.php";

$instituicaoObj = new InstituicaoController();
$administradorObj = new AdministradorController();

$instituicoes = $instituicaoObj->listaInstituicoes();
$administradores = $administradorObj->listaAdmins();
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
                            <h3 class="card-title">Instituições</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm bg-gradient-info" data-toggle="modal"
                                        data-target="#add-instituicao">
                                    <i class="fas fa-plus"></i> Adicionar Instituição
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tabela" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Instituição</th>
                                    <th>Administrador</th>
                                    <th width="30%">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($instituicoes as $instituicao):
                                    $instituicaoAdmins = $instituicaoObj->recuperaAdmins($instituicao->id);
                                    ?>
                                    <tr>
                                        <td><?= $instituicao->instituicao ?></td>
                                        <td>
                                            <?php
                                            if ($instituicaoAdmins) {
                                                echo implode("<br>", $instituicaoAdmins);
                                            } else {
                                                echo "Nenhum administrador vinculado";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn bg-gradient-primary"
                                                    data-id="<?= $instituicaoObj->encryption($instituicao->id) ?>"
                                                    data-instituicao="<?= $instituicao->instituicao ?>"
                                                    onclick="modalEdicao.bind(this)()">
                                                Editar
                                            </button>
                                            <button type="button" class="btn bg-gradient-warning"
                                                    data-id="<?= $instituicaoObj->encryption($instituicao->id) ?>"
                                                    data-instituicao="<?= $instituicao->instituicao ?>"
                                                    onclick="modalAddAdm.bind(this)()">
                                                Vincular Administrador
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Instituição</th>
                                    <th>Administrador</th>
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
    <!-- Modal Cadastro -->
    <div class="modal fade" id="add-instituicao" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="formulario-ajax" data-form="save" action="<?= SERVERURL ?>ajax/administradorAjax.php"
                      method="post">
                    <input type="hidden" name="_method" id="_method" value="insereInstituicao">
                    <div class="modal-header">
                        <h4 class="modal-title">Adicionar nova Instituicao</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="instituicao">Instituição: *</label>
                            <input type="text" name="instituicao" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id="btnSalvar">Adicionar</button>
                    </div>
                    <div class="resposta-ajax"></div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal Cadastro -->
    <!-- Modal Edição -->
    <div class="modal fade" id="edita-instituicao" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="formulario-ajax" data-form="update" action="<?= SERVERURL ?>ajax/administradorAjax.php"
                      method="post">
                    <input type="hidden" name="_method" id="_method" value="editaInstituicao">
                    <div class="modal-header">
                        <h4 class="modal-title titulo-edicao"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="instituicao">Instituição: *</label>
                            <input type="text" name="instituicao" class="form-control" id="instituicao" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="instituicao_id" id="instituicao_id">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id="btnSalvar">Editar</button>
                    </div>
                    <div class="resposta-ajax"></div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal Edição -->
    <!-- Modal Vincular Admin -->
    <div class="modal fade" id="vincular-adm" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="formulario-ajax" data-form="save" action="<?= SERVERURL ?>ajax/administradorAjax.php"
                      method="post">
                    <input type="hidden" name="_method" id="_method" value="vinculaAdm">
                    <div class="modal-header">
                        <h4 class="modal-title titulo-addAdm">Vincular administrador a instituição</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Selecione um ou mais Administradores:</label>
                            <select class="select2bs4" name="administradores[]" multiple id="admins">
                                <option>Selecione...</option>
                                <?php foreach ($administradores as $administrador): ?>
                                    <option value="<?= $administrador->id ?>"><?= $administrador->nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="instituicao_id" id="instituicao-addAdm">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id="btnSalvar">Adicionar</button>
                    </div>
                    <div class="resposta-ajax"></div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal Vincular Admin -->

<?php
$javascript = '
<script>

    function modalEdicao() {
        let titulo = $(".titulo-edicao");
        let cpoInstituicao = $("#instituicao");
        let cpoInstituicaoId = $("#instituicao_id");
        let nomeInstituicao = $(this).data("instituicao");
        let instituicao_id = $(this).data("id");
        
        titulo.text("Editar instituição: " + nomeInstituicao);
        cpoInstituicao.val(nomeInstituicao);
        cpoInstituicaoId.val(instituicao_id);
        $("#edita-instituicao").modal("show");
    }
    
    function modalAddAdm() {
        let titulo = $(".titulo-addAdm");
        let cpoInstituicaoId = $("#instituicao-addAdm");
        let nomeInstituicao = $(this).data("instituicao");
        let instituicao_id = $(this).data("id");
        
        selectionar(instituicao_id);
                
        titulo.text("Vincular administrador a instituição: " + nomeInstituicao);
        cpoInstituicaoId.val(instituicao_id);
        $("#vincular-adm").modal("show");
    }
    
    function selectionar(instituicao){
        
        let dados = {
          _method: "recuperaAdministrador",
          id: instituicao  
        };
        
        let  resultado = $.ajax({
            url:"'.SERVERURL.'ajax/instituicaoAjax.php",
            type: "POST",
            data: dados
        })
        .done(function (resultado){
            let admins = JSON.parse(resultado);
            let select = $("#admins");
            let ids = [];
            if (admins.length > 0){
                for (let admin of admins){
                    ids.push(admin.id);
                }
                select.children().each(function (){
                    if(jQuery.inArray($(this).val(), ids) !== -1) {
                        $(this).attr("selected", true);
                    } else {
                        $(this).removeAttr("selected");
                    }
                });
                select.val(ids).trigger("change");
            } else {
                select.children().each(function (){
                    $(this).removeAttr("selected");
                })
                select.val(null).trigger("change");
            }
        });
    }
</script>';
?>