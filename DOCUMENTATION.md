# phphlba\Routing\Router

```php
/* no custom variables */
```

## Custom constructor

```php
$sample_route = [
	"static": [
		"/": [$Callable, [$arg1, $arg2, $etc], [$guard], [$hook]],
		"/foo": [/* etc */],
		"/foo/bar": [/* etc */],
		"/foo/bar/baz": [/* etc */],
		/* etc */
	],
	"dynamic": [
		"index": [/* etc */],
		"unique_id": [/* etc */],
		"another_uid": [/* etc */],
		/* etc */
	]
];

$sample_regex = "#^(?|(?:/)(*MARK:index)" .
	"|(?:/dynamic-route-([0-9a-z]+))(*MARK:unique_id)" .
	"|(?:/another-dynamic-route-([0-9a-z]+))(*MARK:another_uid)" .
")$#x";

// Router(routes, regex)
use \phplba\Routing\Router;
$router = new Router($sample_route, $sample_regex);
```

## Custom Methods

```php
// if  null, the  default value  used is  a version  of REQUEST_URI  without the
// search parameters (e.g. `?foo=bar&baz`)
$sample_uri = $_SERVER['REQUEST_URI'];

// ->resolve(current_path = <pathname>) : mixed | HttpError 404
$router->resolve($sample_uri);
```

## Example of use

```php
use phplba\Routing\Router;
$router = new Router($sample_route, $sample_regex);
$route = $router->resolve($sample_uri);
// Output $route = { route: mixed, match: array }

// Call to your controller, guard, hook,  service and anything else you may need
// is your responsibility
foreach ($route->route[2] as $guard) {
	$guard();
}
$response = $route->route[0]([...$route->match, $route->route[1]]);
foreach ($route->route[3] as $hook) {
	$hook();
}

echo $response;
```

# phphlba\Routing\RoutesGenerator

```php
/* soon */
```
