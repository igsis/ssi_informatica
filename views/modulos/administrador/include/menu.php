<li class="nav-item">
    <a href="<?= SERVERURL ?>administrador" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Início
        </p>
    </a>
</li>
<?php if ($_SESSION['nivel_acesso_s'] == 2) : ?>
    <li class="nav-item has-treeview" id="gerenciarSistema">
    <a href="#" class="nav-link active">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Gerenciar sistema
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/administrador_lista" class="nav-link" id="administrador_lista">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Administradores
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/categoria_lista" class="nav-link" id="categoria_lista">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Categorias
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/instituicao_lista" class="nav-link" id="instituicao_lista">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Instituições
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/local_lista" class="nav-link" id="local_lista">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Locais
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/tecnico_lista" class="nav-link" id="tecnico_lista">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Técnicos
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/usuario_apagado_lista" class="nav-link" id="usuario_apagado_lista">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Usuários removidos
                </p>
            </a>
        </li>
    </ul>
</li>
<?php endif; ?>
<li class="nav-item">
    <a href="<?= SERVERURL ?>administrador/usuario_lista" id="usuario_lista" class="nav-link">
        <i class="fas fa-users"></i>
        <p>
            Usuários
        </p>
    </a>
</li>
<li class="nav-item has-treeview menu-open">
    <a href="#" class="nav-link active">
        <i class="fas fa-bullhorn"></i>
        <p>
            Chamados
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/chamado_cadastro" class="nav-link">
                <i class="fas fa-plus nav-icon"></i>
                <p>
                    Novo chamado
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/chamado_lista" class="nav-link">
                <i class="fas fa-list-ol nav-icon"></i>
                <p>
                    Lista chamados
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/chamado_busca" class="nav-link">
                <i class="fas fa-search nav-icon"></i>
                <p>
                    Busca chamados
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= SERVERURL ?>administrador/chamado_fechado_lista" class="nav-link" id="local_lista">
                <i class="far fa-times-circle"></i>
                <p>
                    Chamados fechados
                </p>
            </a>
        </li>
    </ul>
</li>