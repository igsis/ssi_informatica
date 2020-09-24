<?php
require_once "./controllers/AdministradorController.php";

$administradorObj = new AdministradorController();

$categorias = $administradorObj->listaCategorias()
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
                        <h3 class="card-title">Categorias</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm bg-gradient-info" onclick="modalAddCategoria()">
                                <i class="fas fa-plus"></i> Adicionar Categoria
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabela" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Categoria</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><?= $categoria->categoria ?></td>
                                    <td>
                                        <button type="button" class="btn bg-gradient-primary float-left mr-2"
                                                data-id="<?= $administradorObj->encryption($categoria->id) ?>"
                                                data-categoria="<?= $categoria->categoria ?>"
                                                onclick="modalEditaCategoria(this)">
                                            Editar
                                        </button>
                                        <form class="formulario-ajax" data-form="delete" action="<?= SERVERURL ?>ajax/administradorAjax.php" method="post">
                                            <input type="hidden" name="_method" value="removeCategoria">
                                            <input type="hidden" name="categoria_id" value="<?= $administradorObj->encryption($categoria->id) ?>">
                                            <button type="submit" class="btn bg-gradient-danger">
                                                Remover
                                            </button>
                                            <div class="resposta-ajax"></div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Categoria</th>
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
<div class="modal fade" id="add-categoria" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="formulario-ajax" data-form="save" action="<?= SERVERURL ?>ajax/administradorAjax.php"
                  method="post">
                <input type="hidden" name="_method" id="method">
                <input type="hidden" name="categoria_id" id="categoria_id">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar nova Instituicao</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="instituicao">Categoria: *</label>
                        <input type="text" name="categoria" class="form-control" id="categoria" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" id="btnSalvar">Salvar</button>
                </div>
                <div class="resposta-ajax"></div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.Modal Cadastro -->

<script type="text/javascript">
    function modalAddCategoria() {
        let modal = $('#add-categoria');
        let method = modal.find('#method');
        let titulo = modal.find('.modal-title');
        let cpoCategoria = modal.find('#categoria');
        let cpoCategoriaId = modal.find('#categoria_id');

        titulo.text('Adicionar Categoria');
        method.val('insereCategoria');
        cpoCategoria.val('');
        cpoCategoriaId.attr('disabled', true)
        modal.modal('show');
    }

    function modalEditaCategoria(e) {
        let modal = $('#add-categoria');
        let method = modal.find('#method');
        let titulo = modal.find('.modal-title');
        let cpoCategoria = modal.find('#categoria');
        let cpoCategoriaId = modal.find('#categoria_id');
        let categoria = $(e).data('categoria');
        let categoria_id = $(e).data('id');

        titulo.text('Editar Categoria ' + categoria);
        method.val('editaCategoria');
        cpoCategoria.val(categoria);
        cpoCategoriaId.attr('disabled', false);
        cpoCategoriaId.val(categoria_id);
        modal.modal('show');
    }
</script>