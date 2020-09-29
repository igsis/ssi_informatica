<?php
$url = SERVERURL.'api/verificadorEmail.php';
$url_local = SERVERURL.'api/locais_espacos.php';

require_once "./controllers/UsuarioController.php";
$usuarioObj = new UsuarioController();

$id = !empty($_GET['id']) ? $_GET['id'] : false;

if ($id){
    $usuario = $usuarioObj->recuperaUsuario($id)->fetch(PDO::FETCH_OBJ);
}
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
                        <h3 class="card-title">Usuários</h3>
                    </div>
                    <!-- /.card-header -->
                    <form class="needs-validation formulario-ajax" data-form="<?= ($id) ? "update" : "save" ?>" action="<?= SERVERURL ?>ajax/usuarioAjax.php" method="post">
                        <input type="hidden" name="_method" value="<?= ($id) ? "editaUsuario" : "insereNovoUsuario" ?>">
                        <input type="hidden" name="pagina" value="administrador/usuario_lista">
                        <?php if ($id): ?>
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php endif; ?>
                        <?php if (!$id): ?>
                            <input type="hidden" name="senha" value="ssi2020">
                            <input type="hidden" name="senha2" value="ssi2020">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="nome">Nome Completo* </label>
                                    <input type="text" id="nome" name="nome" class="form-control" onkeyup="this.value = this.value.toUpperCase();" value="<?= $usuario->nome ?? ''?>" required>
                                    <div class="invalid-feedback">
                                        <strong>Insira seu Nome Completo</strong>
                                    </div>
                                </div>
                                <?php if (!$id) : ?>
                                    <div class="form-group col-md">
                                        <label for="tipo">Funcionário? * </label> <br>
                                        <label><input type="radio" name="jovem_monitor" id="jovem_monitor" value="0" required> Sim</label>&nbsp;&nbsp;
                                        <label><input type="radio" name="jovem_monitor" id="jovem_monitor" value="1"> Não</label>
                                    </div>
                                    <div class="form-group col-md">
                                        <label for="rf_usuario">RF/RG* </label>
                                        <input type="text" id="rgrf_usuario" name="rf_rg" class="form-control" required>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group col-md">
                                    <label for="rf_usuario">Usuário* </label>
                                    <div id='resposta'></div>
                                    <input type="text" id="usuario" name="usuario" class="form-control" maxlength="7" value="<?= $usuario->usuario ?? ''?>" required>
                                    <div class="invalid-feedback">
                                        <strong>Usuário já cadastrado</strong>
                                    </div>
                                </div>
                                <div class="form-group col-md">
                                    <label for="tel_usuario">Telefone *</label>
                                    <input type="text" id="tel_usuario" name="telefone" class="form-control" onkeyup="mascara( this, mtel );" value="<?=$usuario->telefone ?? ''?>" required maxlength="15">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="instituicao">Instituição *</label>
                                    <select class="form-control" name="instituicao_id" id="instituicao" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        $usuarioObj->geraOpcao("instituicoes",$usuario->instituicao_id ?? '');
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md">
                                    <label for="local">Local *</label>
                                    <select class="form-control" id="local" name="local_id">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="email">E-mail Prefeitura *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email1"placeholder="Ex.: smcinfo" <?php if ($id) echo "value='".strstr($usuario->email1,'@',true)."'" ?> required id="email">
                                        <div class="input-group-append">
                                            <span class="input-group-text">@prefeitura.sp.gov.br</span>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        <strong>Email já cadastrado</strong>
                                    </div>
                                </div>
                                <div class="form-group col-md">
                                    <label for="email">E-mail alternativo *</label>
                                    <input type="email" class="form-control" name="email2" placeholder="Email" value="<?=$usuario->email2 ?? ''?>" required id="email">
                                    <div class="invalid-feedback">
                                        <strong>Email já cadastrado</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right" id="cadastra"><?=$id ? "Editar" : "Cadastrar"?></button>
                        </div>
                        <div class="resposta-ajax"></div>
                    </form>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<script>
    const url = `<?= $url ?>`;
    var email = $('#email');
    var usuario = $('#usuario');
    var rf_rg = $('#rgrf_usuario');

    email.blur(function () {
        $.ajax({
            url: url,
            type: 'POST',
            data: {"email": email.val()},

            success: function (data) {
                let emailCampo = document.querySelector('#email');

                if (data.ok) {
                    emailCampo.classList.remove("is-invalid");
                    $("#cadastra").attr('disabled', false);
                } else {
                    emailCampo.classList.add("is-invalid");
                    $("#cadastra").attr('disabled', true);
                }
            }
        })
    });

    rf_rg.blur(function () {
        $.ajax({
            url: url,
            type: 'POST',
            data: {"usuario": usuario.val()},

            success: function (data) {
                let usuarioCampo = document.querySelector('#usuario');

                if (data.ok) {
                    usuarioCampo.classList.remove("is-invalid");
                    $("#cadastra").attr('disabled', false);
                } else {
                    usuarioCampo.classList.add("is-invalid");
                    $("#cadastra").attr('disabled', true);
                }
            }
        })
    });

    function geraUsuarioRf() {

        // pega o valor que esta escrito no RF
        let usuarioRf = document.querySelector("#rgrf_usuario").value;

        // tira os pontos do valor, ficando apenas os numeros
        usuarioRf = usuarioRf.replace(/[^0-9]/g, '');
        usuarioRf = parseInt(usuarioRf);

        // adiciona o d antes do rf
        usuarioRf = "d" + usuarioRf;

        // limita o rf a apenas o d + 6 primeiros numeros do rf
        let usuario = usuarioRf.substr(0, 7);

        // passa o valor para o input
        document.querySelector("[name='usuario']").value = usuario;
    }


    function geraUsuarioRg() {

        // pega o valor que esta escrito no RG
        let usuarioRg = document.querySelector("#rgrf_usuario").value;

        // tira os pontos do valor, ficando apenas os numeros
        usuarioRg = usuarioRg.replace(/[^0-9]/g, '');
        usuarioRg = parseInt(usuarioRg);

        // adiciona o x antes do rg
        usuarioRg = "x" + usuarioRg;

        // limita o rg a apenas o d + 6 primeiros numeros do rf
        let usuario = usuarioRg.substr(0, 7);

        // passa o valor para o input
        document.querySelector("[name='usuario']").value = usuario;

    }

    $("input[name='jovem_monitor']").change(function () {
        $('#rgrf_usuario').attr("disabled", false);

        let jovemMonitor = document.getElementsByName("jovem_monitor");

        for (i = 0; i < jovemMonitor.length; i++) {
            if (jovemMonitor[i].checked) {
                let escolhido = jovemMonitor[i].value;

                if (escolhido == 1) {
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').unmask();
                    $('#rgrf_usuario').attr('maxlength', '');
                    $('#rgrf_usuario').keypress(function (event) {
                        geraUsuarioRg();
                    });
                    $('#rgrf_usuario').blur(function (event) {
                        geraUsuarioRg();
                    });

                } else if (escolhido == 0) {
                    $('#rgrf_usuario').val('');
                    $('#rgrf_usuario').focus();
                    $('#rgrf_usuario').mask('000.000.0');
                    $('#rgrf_usuario').keypress(function (event) {
                        geraUsuarioRf();
                    });
                    $('#rgrf_usuario').blur(function (event) {
                        geraUsuarioRf();
                    });
                }
            }
        }
    });

    const url_local = '<?= $url_local ?>';

    let instituicao = document.querySelector('#instituicao');

    if(instituicao.value != ''){
        let local_id = <?=$usuario->local_id ?? "''"?>;
        getSublinguagem(instituicao.value, local_id)
    }

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();
        getSublinguagem(idInstituicao, '')

        fetch(`${url_local}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();

                }

                if (idInstituicao == 1){
                    let locais = document.querySelector('#local');
                    locais.value = 1;
                    $('#local').attr('readonly', true);
                    $('#local').on('mousedown', function(e) {
                        e.preventDefault();
                    });
                } else {
                    $('#local').unbind('mousedown');
                    $('#local').removeAttr('readonly');
                }
            })
    });

    function getSublinguagem(idInstituicao, selectedId){
        fetch(`${url_local}?instituicao_id=${idInstituicao}`)
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