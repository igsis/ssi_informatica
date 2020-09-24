<?php
$id = $_SESSION['usuario_id_s'];
$url_local = SERVERURL.'api/locais_espacos.php';
require_once "./controllers/UsuarioController.php";
$UsuarioObj = new UsuarioController();
$usuario = $UsuarioObj->recuperaUsuario($id)->fetch();
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Usuário</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="card card-outline card-green">
                    <div class="card-header">
                        <h3 class="card-title">Dados pessoais</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body register-card-body">
                        <form class="form-horizontal formulario-ajax" method="POST" action="<?=SERVERURL?>ajax/usuarioAjax.php" role="form" data-form="update">
                            <input type="hidden" name="_method" value="editaUsuario">
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" maxlength="120" value="<?=$usuario['nome']?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="120" value="<?=$usuario['email']?>" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: *</label>
                                    <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone" name="telefone" maxlength="15" onkeyup="mascara( this, mtel );" value="<?=$usuario['telefone']?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col">
                                    <label for="instituicao">Instituição *</label>
                                    <select class="form-control" name="instituicao_id" id="instituicao" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        $UsuarioObj->geraOpcao("instituicoes",$usuario['instituicao_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label for="local">Local *</label>
                                    <select class="form-control" id="local" name="local_id">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-success btn-block btn-flat">Gravar</button>
                            </div>
                            <div class="resposta-ajax">

                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="card card-outline card-green">
                    <div class="card-header">
                        <h3 class="card-title">Trocar senha</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body register-card-body">
                        <form class="needs-validation formulario-ajax" data-form="update" action="<?=SERVERURL?>ajax/usuarioAjax.php" method="post">
                            <input type="hidden" name="_method" value="trocaSenhaUsuario">
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="senha">Senha: *</label>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="senha">Confirme sua senha: *</label>
                                    <input type="password" class="form-control" id="senha2" name="senha2" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-success btn-block btn-flat">Trocar</button>
                            </div>
                            <div class="resposta-ajax">

                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<script>
    const url = `<?=$url_local?>`;

    let instituicao = document.querySelector("#instituicao");

    if(instituicao.value != ''){
        let local_id = <?=$usuario['local_id']?>;
        getSublinguagem(instituicao.value, local_id)
    }

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();
        getSublinguagem(idInstituicao, '')

        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione... </option>');

                for (const local of locais) {
                    $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                }
            })
    })

    function getSublinguagem(idInstituicao, selectedId){
        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();

                for (const local of locais) {
                    if(selectedId == local.id){
                        $('#local').append(`<option value='${local.id}' selected>${local.local}</option>`).focus();
                    }else{
                        $('#sublinguagem').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                    }
                }
            })
    }

</script>