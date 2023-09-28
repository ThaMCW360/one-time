<?php
use Core\Router;

// Add the routes
Router::add('', ['controller' => 'Home', 'action' => 'index']);
Router::add('api/new', ['controller' => 'Messages', 'action' => 'new']);
Router::add('expired', ['controller' => 'Messages', 'action' => 'expired']);
Router::add('{code}/{adminCode}', ['controller' => 'Messages', 'action' => 'success']);
Router::add('{code}', ['controller' => 'Messages', 'action' => 'read']);
Router::error('404', ['controller' => 'Errors', 'action' => 'e404']);
Router::error('500', ['controller' => 'Errors', 'action' => 'e500']);