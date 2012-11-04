<div id="header">
    <h1><a href="#!/dashboard">Pilule</a></h1>
</div>

<?php if ( !empty( $user ) ): ?>

    <div id="user-nav" class="navbar">
        <ul class="nav btn-group">
            <li class="user-name"><?php echo $user['name']; ?></li>
            <li class="link-settings"><a href="#!/settings"><i class="icon icon-cog"></i> <span class="text">Préférences</span></a></li>
            <li><a title="" href="./logout"><i class="icon icon-off"></i> <span class="text">Déconnexion</span></a></li>
        </ul>
        <ul class="nav btn-group external-frame">
            <li><a title="Revenir au site de Pilule" href="javascript:app.closeExternalFrame();"><i class="icon icon-arrow-left"></i> <span class="text">Revenir à Pilule</span></a></li>
        </ul>
    </div>

<?php endif; ?>