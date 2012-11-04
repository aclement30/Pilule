<div class="alert alert-info" style="margin: 30px 10px 0;">
    Cliquez sur un module pour l'activer ou le désactiver. Lorsque vous avez terminé de personnaliser votre tableau de bord, cliquez sur le bouton <i class="icon-ok"></i>.
</div>

<div class="row-fluid" style="margin-top: 10px; margin-bottom: 0px;">

    <div class="span12 center" style="text-align: left;">
        <ul class="quick-actions dashboard">
            <?php
            // Sélection des modules de l'utilisateur
            $number = 1;

            foreach ($modules as $module) {
                $allowed = true;

                switch ($module['alias']) {
                    case 'registration':
                        if (!$user['registration']) $allowed = false;
                        break;
                    case 'admin':
                        if ( !$user['admin'] ) $allowed = false;
                        break;
                    default:
                        $allowed = true;
                        break;
                }

                if ($allowed) {
                    ?>
                    <li id="module-<?php echo $module[ 'id' ]; ?>" <?php if (!in_array($module['id'], $user_modules)) echo ' class="disabled"'; else echo ' class="enabled"'; ?>>
                        <a href="javascript:app.dashboard.toggleModule(<?php echo $module['id']; ?>);">
                            <img src="<?php echo site_url(); ?>img/modules/<?php echo $module['icon']; ?>" />
                            <div class="title"><?php echo $module['name']; ?></div>
                        </a>
                    </li>
                    <!--
<li id="box-<?php echo $module['id']; ?>" class="module" onMouseOver="javascript:app.dashboard.mouseOver('<?php echo $module['id']; ?>', 1);" onMouseOut="javascript:app.dashboard.mouseOver('<?php echo $module['id']; ?>', 2);">
<a href="<?php if (strpos($module['url'], "s_connect")>0) echo "javascript:app.dashboard.connectTo('".$module['url']."');"; else echo $module['url']; ?>"<?php if (isset($module['target'])) echo ' target="'.$module['target'].'"'; ?> class="img-link"><img src="<?php echo site_url(); ?>images/<?php echo $module['icon']; ?>" /></a>
<div class="title"><a href='<?php if (strpos($module['url'], "s_connect")>0) { echo "javascript:app.dashboard.connectTo('".$module['url']."');"; } else { if (substr($module['url'], 0, 4)!='http') echo site_url(); echo $module['url']; } ?>'><?php echo $module['title']; ?></a></div></li>-->
                    <?php
                    $number++;
                }
            }
            ?>

        </ul>
    </div>
</div>

</div><!-- End of row-fluid -->