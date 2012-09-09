<div class="row-fluid">
    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-time"></i>
								</span>
                <h5>Stockage des données</h5>
            </div>
            <div class="widget-content no-padding">
                <p>Pour accélérer le chargement de Pilule, le système garde une copie de certaines données de votre dossier scolaire sur le serveur de l'Université Laval. Ces données sont automatiquement actualisées lorsqu'elles ont été enregistrées depuis un délai supérieur au délai indiqué ci-dessous.</p>
                <hr>
                <form action="./settings/ajax_configure" method="post" id="form-configure-expiration-delay" target="frame">
                    <div style="font-style:normal; float: left; padding-top: 5px; margin-right: 10px;">Délai d'expiration des données :</div>
                    <div style="float: left;"><select name="delay" class="input-small" onchange="javascript:app.settings.submitForm('expiration-delay');">
                        <option value="<?php echo 3600*24; ?>"<?php if ($param['expiration-delay'] == 3600*24) echo ' selected="selected"'; ?>> 24 heures</option>
                        <option value="<?php echo 3600*12; ?>"<?php if ($param['expiration-delay'] == 3600*12) echo ' selected="selected"'; ?>> 12 heures</option>
                        <option value="<?php echo 3600*6; ?>"<?php if ($param['expiration-delay'] == 3600*6 || $param['expiration-delay'] == '') echo ' selected="selected"'; ?>> 6 heures</option>
                        <option value="<?php echo 3600*5; ?>"<?php if ($param['expiration-delay'] == 3600*5) echo ' selected="selected"'; ?>> 5 heures</option>
                        <option value="<?php echo 3600*2; ?>"<?php if ($param['expiration-delay'] == 3600*2) echo ' selected="selected"'; ?>> 2 heures</option>
                        <option value="<?php echo 60*60; ?>"<?php if ($param['expiration-delay'] == 3600) echo ' selected="selected"'; ?>> 1 heure</option>
                        <option value="<?php echo 60*30; ?>"<?php if ($param['expiration-delay'] == 60*30) echo ' selected="selected"'; ?>> 30 min</option>
                        <option value="<?php echo 60*15; ?>"<?php if ($param['expiration-delay'] == 60*15) echo ' selected="selected"'; ?>> 15 min</option>
                    </select></div>
                    <div style="clear: both;"></div>
                    <input type="hidden" name="param" value="expiration-delay" />

                </form>
            </div>
        </div>
    </div>

    <?php if ($user['idul'] != 'demo') { ?>
    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
								<span class="icon">
									<i class="icon-remove-circle"></i>
								</span>
                <h5>Suppression des données</h5>
            </div>
            <div class="widget-content">
                <p>Vous avez la possibilité de supprimer toutes vos données enregistrées sur le serveur de Pilule. Cela peut être utile si vous avez des problèmes d'utilisation de Pilule ou si les données stockées sont corrompues.</p>
                <p><strong>Attention : vous serez automatiquement déconnecté de Pilule après la suppression de vos données.</strong></p>
                <div style="text-align: center; padding: 5px;"><a href="javascript:app.user.eraseData();" class='btn btn-danger'><i class="icon-remove icon-white"></i> Supprimer les données</a></div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php
if ($autologon == 'yes') { ?>
<div class="row-fluid">
   <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-lock"></i>
                                    </span>
                <h5>Connexion automatique</h5>
            </div>
            <div class="widget-content no-padding">
                <p>Pilule permet la connexion automatique en utilisant Facebook Connect ou Google Accounts. Lorsque vous serez connecté à un de ces deux services avec un compte autorisé, vous pourrez accéder directement à Pilule sans avoir besoin d'entrer votre IDUL et votre NIP. L'authentification aura déjà eu lieu lors de votre connexion à votre compte Facebook ou Google.</p>
                <p><strong>Ce service est encore en phase expérimentale.</strong></p>
                <hr>
                <form action="./settings/ajax_configure" method="post" id="form-configure-autologon" target="frame">
                <div style="float: left;"><input type="checkbox" id="autologon" name="autologon" value="yes"<?php if ($autologon == 'yes') echo ' checked="checked"'; ?> />&nbsp;&nbsp;<label for="autologon" style="font-style: normal; color: black; display: inline;">Activer la connexion automatique</label></div>
                    <div style="float: right; margin-left: 10px;"><a href="javascript:app.settings.submitForm('autologon');" class='btn btn-success'><i class="icon-ok icon-white"></i> Enregistrer</a></div>
                    <div style="clear: both;"></div>
                    <input type="hidden" name="param" value="autologon" />
                </form>
            </div>
        </div>
   </div>

    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-user"></i>
                                    </span>
                <h5>Comptes autorisés</h5>
            </div>
            <div class="widget-content no-padding">
                <div style="float: left; width: 130px;"><img src="<?php echo site_url(); ?>images/facebook-logo.jpg" alt="Facebook" height="40" /></div>
                <div id="fb-account">
                    <?php if (isset($fbuid) and $fbuid) { ?>
                    <div style="float: left; margin-top: 10px; margin-right: 20px;"><a href="http://www.facebook.com/profile.php?id=<?php echo $fbuid; ?>" target="_blank"><?php echo $fbname; ?></a></div>
                    <div style="float: right; margin-top: 5px;"><a href="javascript:app.settings.unlinkAccount('facebook');" class='btn btn-danger'><i class="icon-remove icon-white"></i> Supprimer</a></div>
                    <?php } else { ?>
                    <div style="float: left; margin-top: 10px; margin-right: 20px;">Aucun compte autorisé</div>
                    <div style="float: right; margin-top: 5px;"><a href="javascript:document.location='<?php echo site_url()."cfacebook/auth/u/".base64_encode(site_url()."settings/s_linkaccount/account/facebook"); ?>';" class='btn'><i class="icon-plus"></i> Ajouter</a></div>
                    <?php } ?>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
} ?>
<iframe name="frame" style="width: 0px; height: 0px;" frameborder="0"></iframe>