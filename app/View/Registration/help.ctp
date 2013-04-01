<h5>Comment fonctionne l'inscription sur Pilule ?</h5>
<p>L'inscription se déroule en deux étapes :</p>
<ol>
	<li>Tout d'abord, vous sélectionnez les cours auxquels vous désirez vous inscrire et vous les ajouter à votre sélection de cours.</li>
	<li>Ensuite, en cliquant sur le bouton <strong>Inscription</strong>, une demande d'inscription sera envoyée à Capsule pour les cours sélectionnés.</li>
</ol>
<p>Capsule renvoie un statut d'inscription pour chaque cours : inscrit, refusé, liste d'attente, etc. Ce résultat ainsi que le message d'erreur éventuel sera affiché lorsque la demande d'inscription est complétée. Le processus complet dure moins d'une minute.</p>
<p>Si l'inscription à un cours se déroule avec succès, ce cours sera immédiatement affiché dans le bloc <strong>Cours inscrits</strong> dans la colonne de gauche, ainsi que dans votre horaire pour la session d'inscription.</p>
<p>Nous vous recommandons de vérifier que l'inscription s'est bien déroulée en cliquant sur le bouton <strong>Capsule - Inscription</strong> dans la colonne de gauche. Ce bouton vous mènera directement à la page de choix de cours sur le site de Capsule.</p>
<p>Note : les cours sélectionnés sont reliés à chaque session d'inscription et sont mémorisés sur le serveur. Il vous est donc possible de faire votre sélection de cours avant la date officielle d'inscription, puis au moment opportun, de vous inscrire aux cours sélectionnés.</p>

<hr>

<h5>Est-ce que l'inscription est sécuritaire ?</h5>

<p>Tout comme pour le reste du site, Pilule inscrit vos cours via Capsule comme vous le feriez normalement. Il n'y a pas d'accès spécial ou privilégié pour l'inscription des cours. La différence majeure réside dans la présentation des cours et dans l'expérience utilisateur au moment de l'inscription. Tout le reste du processus d'inscription (mise en file d'attente, vérification des prérequis, etc) s'effectue sur le serveur de Capsule comme à l'habitude.</p>

<hr>

<h5>1. Liste de choix de cours</h5>
<?php echo $this->element( 'registration/help/step1' ); ?>

<hr>

<h5>2. Informations sur le cours</h5>
<?php echo $this->element( 'registration/help/step2' ); ?>

<hr>

<h5>3. Sélection de cours</h5>
<?php echo $this->element( 'registration/help/step3' ); ?>

<hr>

<h5>4. Cours inscrits</h5>
<?php echo $this->element( 'registration/help/step4' ); ?>

<hr>

<h5>5. Recherche de cours</h5>
<?php echo $this->element( 'registration/help/step5' ); ?>