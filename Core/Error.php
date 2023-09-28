<?php

namespace Core;
use Core\Config;
use Core\Functions;

/**
 * Error and exception handler
 *
 * PHP version 5.4
 */
class Error {

	public static function setError() {
		error_reporting(E_ALL);
		set_error_handler('Core\Error::errorHandler');
		set_exception_handler('Core\Error::exceptionHandler');
	}

	public static function errorHandler($level, $message, $file, $line) {
		if (error_reporting() !== 0) {
			// to keep the @ operator working
			throw new \ErrorException($message, 0, $level, $file, $line);
		}
	}

	public static function exceptionHandler($exception) {
		// Code is 404 (not found) or 500 (general error)
		$code = $exception->getCode();
		if ($code != 404) {
			$code = 500;
		}
		http_response_code($code);

		if (Config::SHOW_ERRORS) {
			echo "<h1>Fatal error</h1>";
			echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
			echo "<p>Message: '" . $exception->getMessage() . "'</p>";
			echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
			echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
		} else {
			$log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
			ini_set('error_log', $log);

			$message = "Uncaught exception: '" . get_class($exception) . "'";
			$message .= " with message '" . $exception->getMessage() . "'";
			$message .= "\nStack trace: " . $exception->getTraceAsString();
			$message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
			error_log($message);
			Functions::Redirect(Config::BASEDIR . '/' . $code, false);

		}
	}
}
