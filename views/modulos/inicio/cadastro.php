<?php
$url = SERVERURL.'api/verificadorEmail.php';
$url_local = SERVERURL.'api/locais_espacos.php';

require_once "./controllers/UsuarioController.php";
$objUsuario = new UsuarioController();
?>
<div class="login-page">
    <div class="card">
        <div class="card-header bg-dark">
            <a href="<?= SERVERURL ?>inicio" class="brand-link">
                <img src="<?= SERVERURL ?>views/dist/img/logo_ssi.png" alt="SisContrat Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?= NOMESIS ?></span>
            </a>
        </div>
        <div class="card-body register-card-body">
            <h5 class="login-box-msg">Efetue seu Cadastro</h5>
            <form class="needs-validation formulario-ajax" data-form="save" action="<?= SERVERURL ?>ajax/usuarioAjax.php" method="post">
                <input type="hidden" name="_method" value="insereNovoUsuario">

                <div class="row">
                    <div class="form-group col">
                        <label for="nome">Nome Completo* </label>
                        <input type="text" id="nome" name="nome" class="form-control" required>
                        <div class="invalid-feedback">
                            <strong>Insira seu Nome Completo</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col">
                        <label for="rf_usuario">RF* </label>
                        <input type="text" id="rgrf_usuario" name="rf_rg" class="form-control" required>
                    </div>

                    <div class="form-group col">
                        <label for="rf_usuario">Usuário* </label>
                        <div id='resposta'></div>
                        <input type="text" id="usuario" name="usuario" class="form-control" maxlength="7" required readonly>
                        <div class="invalid-feedback">
                            <strong>Usuário já cadastrado</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col">
                        <label for="instituicao">Instituição *</label>
                        <select class="form-control" name="instituicao_id" id="instituicao" required>
                            <option value="">Selecione uma opção...</option>
                            <?php
                            $objUsuario->geraOpcao("instituicoes");
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
                <div class="row">
                    <div class="form-group col">
                        <label for="email">E-mail *</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required id="email">
                        <div class="invalid-feedback">
                            <strong>Email já cadastrado</strong>
                        </div>
                    </div>
                    <div class="form-group col">
                        <label for="tel_usuario">Telefone* </label>
                        <input type="text" id="tel_usuario" name="telefone" class="form-control" onkeyup="mascara( this, mtel );" required maxlength="15">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col">
                        <label>Insira sua senha *</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                        <div class="invalid-feedback">
                            <strong>Insira sua Senha</strong>
                        </div>
                    </div>
                    <div class="form-group col">
                        <label>Confirme sua senha *</label>
                        <input type="password" class="form-control" id="senha2" name="senha2" placeholder="Confirme sua Senha" required>
                        <div class="invalid-feedback">
                            <strong>Confirme sua Senha</strong>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="cadastra">Cadastrar</button>
                </div>
                <div class="resposta-ajax">

                </div>
            </form>

            <div class="mb-0 text-center">
                <a href="<?= SERVERURL ?>" class="text-center">Já possuo cadastro</a>
            </div>
        </div>
        <div class="card-footer bg-light-gradient text-center">
            <img src="<?= SERVERURL ?>views/dist/img/CULTURA_HORIZONTAL_pb_positivo.png" alt="logo cultura">
        </div>
    </div><!-- /.card -->
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

    $( document ).ready(function() {
        $('#rgrf_usuario').mask('000.000.0');
        $('#rgrf_usuario').keypress(function (event) {
            geraUsuarioRf();
        });
        $('#rgrf_usuario').blur(function (event) {
            geraUsuarioRf();
        });
    });

    const url_local = '<?= $url_local ?>';

    let instituicao = document.querySelector('#instituicao');

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();
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
                    locais.value = 2;
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
</script>