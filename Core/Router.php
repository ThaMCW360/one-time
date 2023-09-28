<?php
namespace Core;

use Core\Config;

class Router {

	/**
	 * Associative array of routes (the routing table)
	 * @var array
	 */
	protected static $routes = [];

	/**
	 * Parameters from the matched route
	 * @var array
	 */
	protected static $params = [];

	/**
	 * Add a route to the routing table
	 *
	 * @param string $route  The route URL
	 * @param array  $params Parameters (controller, action, etc.)
	 *
	 * @return void
	 */
	public static function add($route, $params = []) {
		// Convert the route to a regular expression: escape forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		// Convert variables e.g. {controller}
		$route = preg_replace('/\{([a-zA-Z0-9]*)\}/', '(?P<\1>[a-zA-Z0-9]*)', $route);

		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		// Add start and end delimiters, and case insensitive flag
		$route = '/^' . $route . '$/i';

		self::$routes[$route] = $params;
	}

	public static function error($route, $params = []) {
		// Convert the route to a regular expression: escape forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		// Convert variables e.g. {controller}
		$route = preg_replace('/\{([a-zA-Z0-9]*)\}/', '(?P<\1>[a-zA-Z0-9]*)', $route);

		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		// Add start and end delimiters, and case insensitive flag
		$route = '/^' . $route . '$/i';

		self::$routes[$route] = $params;
	}

	/**
	 * Get all the routes from the routing table
	 *
	 * @return array
	 */
	public static function getRoutes() {
		return $this->routes;
	}

	/**
	 * Match the route to the routes in the routing table, setting the $params
	 * property if a route is found.
	 *
	 * @param string $url The route URL
	 *
	 * @return boolean  true if a match found, false otherwise
	 */
	public static function match($url) {
		foreach (self::$routes as $route => $params) {
			if (preg_match($route, $url, $matches)) {
				// Get named capture group values
				foreach ($matches as $key => $match) {
					if (is_string($key)) {
						$params[$key] = $match;
					}
				}

				self::$params = $params;
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the currently matched parameters
	 *
	 * @return array
	 */
	public static function getParams() {
		return self::$params;
	}

	/**
	 * Dispatch the route, creating the controller object and running the
	 * action method
	 *
	 * @param string $url The route URL
	 *
	 * @return void
	 */
	public static function dispatch() {
		$url = Config::url();
		if (self::match($url)) {
			$controller = self::$params['controller'];
			$controller = self::convertToStudlyCaps($controller);
			$controller = preg_replace('/\.{1}/', '\\', $controller);
			$controller = preg_replace('/\/{1}/', '\\', $controller);
			$controller = self::getNamespace() . $controller;

			if (class_exists($controller)) {
				$controller_object = new $controller(self::$params);

				$action = self::$params['action'];
				$action = self::convertToCamelCase($action);

				if (is_callable([$controller_object, $action])) {
					unset(self::$params["controller"]);
					unset(self::$params["action"]);
					$obj = json_decode(json_encode(self::$params, false));
					$request = json_decode(json_encode($_REQUEST, false));
					echo ($controller_object->$action($obj, $request));

				} else {
					throw new \Exception("Method $action (in controller $controller) not found");
				}
			} else {
				throw new \Exception("Controller class $controller not found");
			}
		} else {
			throw new \Exception('No route matched.', 404);
		}
	}

	protected static function convertToStudlyCaps($string) {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	protected static function convertToCamelCase($string) {
		return lcfirst(self::convertToStudlyCaps($string));
	}

	protected static function getNamespace() {
		$namespace = 'App\Controllers\\';

		if (array_key_exists('namespace', self::$params)) {
			$namespace .= self::$params['namespace'] . '\\';
		}

		return $namespace;
	}
}
