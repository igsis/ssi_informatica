<!--<li class="nav-item has-treeview menu-open">
    <a href="#" class="nav-link active">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Starter Pages
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" class="nav-link active">
                <i class="far fa-circle nav-icon"></i>
                <p>Active Page</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inactive Page</p>
            </a>
        </li>
    </ul>
</li>-->
<li class="nav-item">
    <a href="<?= SERVERURL ?>inicio" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Início
        </p>
    </a>
</li>
<?php if ($_SESSION['nivel_acesso_s'] == 2): ?>
    <li class="nav-item">
        <a href="<?= SERVERURL ?>administrador" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>
                Ambiente Administrador
            </p>
        </a>
    </li>
<?php endif ?>
<li class="nav-item">
    <a href="<?= SERVERURL ?>chamado" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Ambiente Chamados
        </p>
    </a>
</li>
