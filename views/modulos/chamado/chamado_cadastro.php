<?php
require_once "./controllers/UsuarioController.php";
require_once "./controllers/LocalController.php";

$id = !empty($_GET['id']) ? $_GET['id'] : false;

$usuarioObj = new UsuarioController();
$usuario = $usuarioObj->recuperaUsuario($_SESSION['usuario_id_s'])->fetchObject();

$localObj = new LocalController();
$admin = $localObj->recuperaAdministrador('', $usuario->local_id)->fetchObject();

?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastro de chamado</h1>
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
                    <form class="formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/chamadoAjax.php"
                          role="form" data-form="<?= ($id) ? "update" : "save" ?>">
                        <input type="hidden" name="_method" value="<?= ($id) ? "editar" : "cadastrar" ?>">
                        <input type="hidden" name="pagina" value="chamado">
                        <input type="hidden" name="usuario_id" value="<?= $usuario->id ?>">
                        <input type="hidden" name="administrador_id" value="<?= $admin->administrador_id ?>">
                        <input type="hidden" name="prioridade_id" value="1">
                        <input type="hidden" name="local_id" value="<?= $usuario->local_id ?>">
                        <input type="hidden" name="status_id" value="1">
                        <?php if (!$id): ?>
                            <input type="hidden" name="data_abertura" value="<?= date('Y-m-d H:i:s') ?>">
                        <?php endif; ?>
                        <?php if ($id): ?>
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md">
                                    <label for="telefone">Telefone: *</label>
                                    <input type="text" id="telefone" name="telefone" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" required
                                           value="<?= $usuario->telefone ?? "" ?>" maxlength="15">
                                </div>
                                <div class="form-group col-md">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" id="email" name="email" class="form-control" maxlength="120"
                                           placeholder="Digite o E-mail" value="<?= $usuario->email ?? '' ?>" required>
                                </div>
                                <div class="form-group col-md">
                                    <label for="contato">Contato: *</label>
                                    <input type="text" id="contato" name="contato" class="form-control" maxlength="120"
                                           placeholder="Digite o nome do contato no local"
                                           value="<?= $usuario->contato ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>
                                            Categorias: *
                                            <button class="btn btn-default rounded-circle" type="button" data-toggle="modal"
                                                    data-target="#infoCategorias">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </label>
                                        <select class="form-control select2bs4" style="width: 100%;"
                                                name="categoria_id">
                                            <option value="">Selecione uma opção...</option>
                                            <?php
                                            $usuarioObj->geraOpcao("categorias", "", true);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="descricao">Descrição: *</label>
                                        <textarea name="descricao" id="descricao" class="form-control" rows="3"
                                                  required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="resposta-ajax"></div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Gravar</button>
                        </div>
                        <!-- /.card-footer -->
                        <div class="resposta-ajax"></div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- Modal -->
<div class="modal fade" id="infoCategorias" tabindex="-1" role="dialog" aria-labelledby="infoCategorias"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalTitulo">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Alvenaria</h5>
                <ul>
                    <li>Reboco em paredes, muros, etc.</li>
                    <li>Assentamento de tijplos e blocos.</li>
                    <li>Impermeabilzação em geral.</li>
                </ul>
                <h5>Carpintaria</h5>
                <ul>
                    <li>Madeiramento.</li>
                </ul>
                <h5>Elétrica</h5>
                <ul>
                    <li>Troca de lâmpadas.</li>
                    <li>Instalação de interruptores.</li>
                    <li>Iluminação de emergência.</li>
                </ul>
                <h5>Geral</h5>
                <ul>
                    <li>Pesquisa de materiais como preço, qualidade, tipo, quantidade e descrição.</li>
                    <li>Compra dos materiais.</li>
                    <li>Limpeza.</li>
                    <li>Organização / Conservação.</li>
                    <li>Itens que não entram em classificações anteriores.</li>
                </ul>
                <h5>Hidráulica</h5>
                <ul>
                    <li>Conserto de vazamentos em tubulações.</li>
                    <li>Troca de reparo em válvulas de descarga.</li>
                    <li>Troca de válvulas de descargas, torneiras, registro, etc.</li>
                    <li>Calhas e rufos.</li>
                </ul>
                <h5>Jardinagem</h5>
                <ul>
                    <li>Corte de grama.</li>
                </ul>
                <h5>Manutenção de equipamentos</h5>
                <ul>
                    <li>Microondas.</li>
                    <li>Geladeiras.</li>
                </ul>
                <h5>Marcenaria</h5>
                <ul>
                    <li>Troca de fechaduras e puxadores.</li>
                    <li>Troca de folha de porta.</li>
                    <li>Confecção de molduras.</li>
                    <li>Colagem de folha de revestimento como fórmica, post formic e lâmina de madeira.</li>
                    <li>Polimento com cera / verniz.</li>
                    <li>Cordões de acabamento.</li>
                </ul>
                <h5>Pintura</h5>
                <ul>
                    <li>Aplicação de verniz.</li>
                    <li>Aplicação de latex acrílico e a base d'agua.</li>
                    <li>Esmalte e tinta à óleo em madeiras, portas, janelas, grades, etc.</li>
                    <li>Criação a base de cal.</li>
                </ul>
                <h5>Serralheria</h5>
                <ul>
                    <li>Consertos gerais.</li>
                </ul>
                <h5>Telhado</h5>
                <ul>
                    <li>Vazamentos.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>