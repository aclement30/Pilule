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
	Router::connect( '/login', array( 'controller' => 'users', 'action' => 'login' ) );
	Router::connect( '/logout', array( 'controller' => 'users', 'action' => 'logout' ) );

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect( '/support/:page', array( 'controller' => 'pages', 'action' => 'display', 'support' ), array( 'pass' => array( 'page' ) ) );
	Router::connect( '/pages/*', array( 'controller' => 'pages', 'action' => 'display'));

	Router::connect( '/schedule/:semester', array( 'controller' => 'schedule', 'action' => 'index' ), array( 'pass' => array( 'semester' ) ) );

	Router::connect( '/services/:service', array( 'controller' => 'services', 'action' => 'connect' ), array( 'pass' => array( 'service' ) ) );

	Router::connect( '/registration/getCourseInfo/:code', array( 'controller' => 'registration', 'action' => 'getCourseInfo' ), array( 'pass' => array( 'code' ) ) );

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
