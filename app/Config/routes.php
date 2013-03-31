<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	
	Router::parseExtensions( 'json' );

	Router::connect( '/', array( 'controller' => 'users', 'action' => 'dashboard' ) );
	Router::connect( '/dashboard', array( 'controller' => 'users', 'action' => 'dashboard' ) );

	Router::connect( '/dossier-scolaire', array( 'controller' => 'studies', 'action' => 'index' ) );
	Router::connect( '/dossier-scolaire/rapport-cheminement', array( 'controller' => 'studies', 'action' => 'details' ) );
	Router::connect( '/dossier-scolaire/rapport-cheminement/:id', array( 'controller' => 'studies', 'action' => 'details' ), array( 'pass' => array( 'id' ) ) );
	Router::connect( '/dossier-scolaire/releve-notes', array( 'controller' => 'studies', 'action' => 'report' ) );

	Router::connect( '/frais-scolarite', array( 'controller' => 'tuitions', 'action' => 'index' ) );
	Router::connect( '/frais-scolarite/releve', array( 'controller' => 'tuitions', 'action' => 'details' ) );
	Router::connect( '/frais-scolarite/:semester', array( 'controller' => 'tuitions', 'action' => 'details' ), array( 'pass' => array( 'semester' ) ) );

	Router::connect( '/preferences', array( 'controller' => 'settings', 'action' => 'index' ) );

	Router::connect( '/connexion', array( 'controller' => 'users', 'action' => 'login' ) );

	//Router::connect( '/logout', array( 'controller' => 'users', 'action' => 'logout' ) );
	Router::connect( '/deconnexion', array( 'controller' => 'users', 'action' => 'logout' ) );

	// Redirection for old routes
	Router::redirect( '/welcome', array( 'controller' => 'users', 'action' => 'dashboard' ) );
	Router::redirect( '/login', array( 'controller' => 'users', 'action' => 'login' ) );
	Router::redirect( '/schedule', array( 'controller' => 'schedule', 'action' => 'index' ) );
	Router::redirect( '/schedule/timetable', array( 'controller' => 'schedule', 'action' => 'index' ) );
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect( '/support/:page', array( 'controller' => 'pages', 'action' => 'display', 'support' ), array( 'pass' => array( 'page' ) ) );
	Router::connect( '/pages/*', array( 'controller' => 'pages', 'action' => 'display'));

	Router::connect( '/schedule/:semester', array( 'controller' => 'schedule', 'action' => 'index' ), array( 'pass' => array( 'semester' ) ) );
	Router::connect( '/horaire/:semester', array( 'controller' => 'schedule', 'action' => 'index' ), array( 'pass' => array( 'semester' ) ) );
	Router::connect( '/horaire', array( 'controller' => 'schedule', 'action' => 'index' ) );

	Router::connect( '/services/:service', array( 'controller' => 'services', 'action' => 'connect' ), array( 'pass' => array( 'service' ) ) );

	Router::connect( '/choix-cours/aide', array( 'controller' => 'registration', 'action' => 'help' ) );
	Router::connect( '/choix-cours/recherche', array( 'controller' => 'registration', 'action' => 'search' ) );
	Router::connect( '/choix-cours/resultats/:token', array( 'controller' => 'registration', 'action' => 'results' ), array( 'pass' => array( 'token' ) ) );
	Router::connect( '/choix-cours/:semester/:programId', array( 'controller' => 'registration', 'action' => 'index' ), array( 'pass' => array( 'semester', 'programId' ) ) );
	Router::connect( '/choix-cours/:semester', array( 'controller' => 'registration', 'action' => 'index' ), array( 'pass' => array( 'semester' ) ) );
	Router::connect( '/choix-cours', array( 'controller' => 'registration', 'action' => 'index' ) );
	Router::connect( '/registration/getCourseInfo/:code', array( 'controller' => 'registration', 'action' => 'getCourseInfo' ), array( 'pass' => array( 'code' ) ) );
	Router::connect( '/inscription', array( 'controller' => 'registration', 'action' => 'enableBetaRegistration' ) );

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
