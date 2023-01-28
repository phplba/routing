# phplba/routing

phplba/routing is a routing library that determines the right static or dynamic route based on a provided url in the most effective way.

You can associate any type of value to a route, the router just returns the right value and it is up to you to manipulate this return in order to call your constructors, hooks and any other elements you may need.

It is also your responsibility (for performance purposes) to provide the static and dynamic arrays and the regular expression needed for dynamic routing.
    
## 🛠️ Install as Dependencies    
```bash
composer require phplba/routing
```

## 🧑🏻‍💻 Usage
```php
use phplba/Routing/Router;

$router = new Router($routes, $regex);
$selected_route = $router->resolve($uri);
```
You can find more information on the [documentation](DOCUMENTATION.md).

## ❤️ Support  
A simple star to this project repo is enough to keep me motivated on this project for days. If you find your self very much excited with this project let me know with a discord MP.

If you have any questions, feel free to reach out to me on [Discord](https://github.com/Angelisium) (Angelisium#1997).

## ➤ License
Distributed under the MIT License. See [LICENSE](LICENSE) for more information.