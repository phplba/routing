<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phplba\Routing\Router;
use phplba\Routing\Error\RoutingError;

const ROUTES = [
	'static' => [
		'/' => 'Root',
		'/Foo' => 'Foo',
		'/foo' => 'foo',
		'/foo/bar/baz' => 'FooBarBaz',
	],
	'dynamic' => [
		'd1' => 'DynamicNumber',
		'd2' => 'DynamicStringLower',
		'd3' => 'DynamicStringUpper',
		'd4' => 'DynamicStringLowerAndUpper',
		'd5' => 'DynamicSlug',
		'd6' => 'DynamicWord',
	],
];

// prettier-ignore
const REGEX = '#^(?' .
	'|(?:/found-but-not-found)(*MARK:d0)' .
	'|(?:/number-([0-9]+))(*MARK:d1)' .
	'|(?:/string-([a-z]+))(*MARK:d2)' .
	'|(?:/string-([A-Z]+))(*MARK:d3)' .
	'|(?:/string-([a-zA-Z]+))(*MARK:d4)' .
	'|(?:/slug-([0-9a-zA-Z\-]+))(*MARK:d5)' .
	'|(?:/word-(fr|en))(*MARK:d6)' .
")$#x";

final class RouterTest extends TestCase {
	public function testRoutingWithStaticRoute(): void {
		$router = new Router(ROUTES, REGEX);
		$this->assertInstanceOf(Router::class, $router);

		$route_1 = $router->resolve('/');
		$route_2 = $router->resolve('/Foo');
		$route_3 = $router->resolve('/foo');
		$route_4 = $router->resolve('/foo/bar/baz');

		$this->assertEquals('Root', $route_1['route']);
		$this->assertEquals('Foo', $route_2['route']);
		$this->assertEquals('foo', $route_3['route']);
		$this->assertEquals('FooBarBaz', $route_4['route']);
	}

	public function testRouteWithDynamicNumber(): void {
		$router = new Router(ROUTES, REGEX);

		$route_1 = $router->resolve('/number-999999');
		$route_2 = $router->resolve('/number-0123456789');
		$route_3 = $router->resolve('/number-777');

		$this->assertEquals('DynamicNumber', $route_1['route']);
		$this->assertEquals(['999999'], $route_1['matches']);

		$this->assertEquals('DynamicNumber', $route_2['route']);
		$this->assertEquals(['0123456789'], $route_2['matches']);

		$this->assertEquals('DynamicNumber', $route_3['route']);
		$this->assertEquals(['777'], $route_3['matches']);
	}

	public function testRouteWithDynamicString(): void {
		$router = new Router(ROUTES, REGEX);

		$route_1 = $router->resolve('/string-foo');
		$route_2 = $router->resolve('/string-BAAAAR');
		$route_3 = $router->resolve('/string-baAAzzzz');

		$this->assertEquals('DynamicStringLower', $route_1['route']);
		$this->assertEquals(['foo'], $route_1['matches']);

		$this->assertEquals('DynamicStringUpper', $route_2['route']);
		$this->assertEquals(['BAAAAR'], $route_2['matches']);

		$this->assertEquals('DynamicStringLowerAndUpper', $route_3['route']);
		$this->assertEquals(['baAAzzzz'], $route_3['matches']);
	}

	public function testRouteWithDynamicSlug(): void {
		$router = new Router(ROUTES, REGEX);

		$route_1 = $router->resolve('/slug-foo-Bar-BAZZZ');
		$route_2 = $router->resolve('/slug-BAR-BAZ-FOO');
		$route_3 = $router->resolve('/slug-Foooo-barrr-bazzz');

		$this->assertEquals('DynamicSlug', $route_1['route']);
		$this->assertEquals(['foo-Bar-BAZZZ'], $route_1['matches']);

		$this->assertEquals('DynamicSlug', $route_2['route']);
		$this->assertEquals(['BAR-BAZ-FOO'], $route_2['matches']);

		$this->assertEquals('DynamicSlug', $route_3['route']);
		$this->assertEquals(['Foooo-barrr-bazzz'], $route_3['matches']);
	}

	public function testRouteWithDynamicWord(): void {
		$router = new Router(ROUTES, REGEX);

		$route_1 = $router->resolve('/word-fr');
		$route_2 = $router->resolve('/word-en');

		$this->assertEquals('DynamicWord', $route_1['route']);
		$this->assertEquals(['fr'], $route_1['matches']);

		$this->assertEquals('DynamicWord', $route_2['route']);
		$this->assertEquals(['en'], $route_2['matches']);
	}

	public function testRouteNotFound(): void {
		$this->expectException(RoutingError::class);
		$this->expectExceptionCode(404);

		$router = new Router(ROUTES, REGEX);
		$router->resolve('/route-not-found');
	}

	public function testRouteFoundButNotFound(): void {
		$this->expectException(RoutingError::class);
		$this->expectExceptionCode(500);

		$router = new Router(ROUTES, REGEX);
		var_dump($router->resolve('/found-but-not-found'));
	}
}
