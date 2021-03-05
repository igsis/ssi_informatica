
<div class="login-page">
    <div class="card">
        <div class="card-header bg-dark">
            <a href="<?= SERVERURL ?>inicio" class="brand-link">
                <img src="<?= SERVERURL ?>views/dist/img/logo_ssi.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?= NOMESIS ?></span>
            </a>
        </div>
        <div class="card-body register-card-body">
            <p><?= isset($message) ? $message : '' ?></p>
            <form class="form-horizontal formulario-ajax" method="POST" action="<?= SERVERURL ?>ajax/usuarioAjax.php" role="form"
                  data-form="recover">
                
                <div class="resposta-ajax"></div>
            </form>
            <div class="mb-0 text-left">
                <p class="mb-1">
                    <a href="<?= SERVERURL ?>">Voltar a Tela de Login</a>
                </p>
            </div>
        </div>
        <div class="card-footer bg-light-gradient text-center">
            <img src="<?= SERVERURL ?>views/dist/img/CULTURA_HORIZONTAL_pb_positivo.png" alt="logo cultura">
        </div>
    </div><!-- /.card -->
</div>