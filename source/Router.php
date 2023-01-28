<?php
namespace phplba\Routing;

use phplba\Routing\Error\RoutingError;

class Router {
	public array $static;
	public array $dynamic;

	public function __construct(array $routes, public string $regex) {
		$this->static = $routes['static'];
		$this->dynamic = $routes['dynamic'];
	}

	public function resolve(string $URL = null) {
		if ($URL == null) {
			$URL = self::defaultURL();
		}
		if (isset($this->static[$URL])) {
			return [
				'route' => $this->static[$URL],
				'matches' => [],
			];
		}
		if (!preg_match($this->regex, $URL, $matches)) {
			throw new RoutingError(
				"No route was found for this URL: $URL",
				404
			);
		}
		if (isset($this->dynamic[$matches['MARK']])) {
			$route = $this->dynamic[$matches['MARK']];
			unset($matches['MARK']);
			return [
				'route' => $route,
				'matches' => $matches,
			];
		}
		throw new RoutingError(
			"The route was matched in the regular expression, but no matching route was found. (URL: $URL)",
			500
		);
	}

	public static function defaultURL(): string {
		$URI = $_SERVER['REQUEST_URI'];
		if (false !== ($I = strpos($URI, '?'))) {
			$URI = substr($URI, 0, $I);
		}
		return rawurldecode($URI);
	}
}
