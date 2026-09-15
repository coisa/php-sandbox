# FastForward integration guide

## Where the application begins

Both entrypoints load `config/container.php`, which calls `App\Bootstrap::create()`.
The bootstrap wraps `config/settings.php` in `FastForward\Config\ArrayConfig`,
registers explicit factories for the sandbox classes and appends
`FrameworkServiceProvider::class`.

~~~php
use FastForward\Config\ArrayConfig;
use FastForward\Framework\ServiceProvider\FrameworkServiceProvider;

use function FastForward\Container\container;

$config = new ArrayConfig(require __DIR__ . '/../config/settings.php');
$container = container($config, FrameworkServiceProvider::class);
~~~

`container()` in the locked release is **variadic**: pass each configuration or
provider as a separate argument. An array of provider class names is not an
initializer for this function.

The application factory is in [`src/Bootstrap.php`](../src/Bootstrap.php).
Earlier providers take precedence. Its optional `ClockInterface` argument
registers the test clock before the framework clock provider. Factories resolve
services through PSR-11, while application classes receive their dependencies
in constructors.

## Component map

These are the versions in the committed lock file and the APIs exercised here,
verified against the released source rather than repository descriptions:

| Package | Locked release | Exercised surface |
| --- | --- | --- |
| `fast-forward/framework` | 1.4.0 | `FrameworkServiceProvider` composes HTTP, event and clock providers. |
| `fast-forward/container` | 1.6.0 | Variadic `container()` and `ArrayServiceProvider`; explicit overrides and cached resolution. |
| `fast-forward/config` | 1.4.0 | `ArrayConfig`, `ConfigInterface`, dot-notation settings and the container adapter. |
| `fast-forward/clock` | 1.0.0 | Framework `SystemClock` in UTC and injected `FrozenClock` in tests and the HTTP example. |
| `fast-forward/enum` | 0.1.0 | `HasValues` on the sandbox's own `ExperimentStatus`. |
| `fast-forward/event-dispatcher` | 1.0.0 | PSR-14 dispatch and a typed invokable listener configured under `ListenerProviderInterface::class`. |
| `fast-forward/iterators` | 1.2.0 | `FastForward\Iterator\ChunkedIteratorAggregate`, including the final partial chunk. |
| `fast-forward/defer` | 1.0.0 | `scope()` and `DeferInterface`, with callbacks observed in reverse registration order. |
| `fast-forward/http` | 1.1.0 | Aggregate HTTP provider used through the framework. |
| `fast-forward/http-factory` | 1.2.0 | PSR-17 factories, payload response factory and `ServerRequestCreatorInterface`. |
| `fast-forward/http-message` | 1.4.0 | `JsonStream` for the responder and JSON response types from the FastForward factory. |
| `fast-forward/http-client` | 1.1.0 | PSR-18 client resolved with an explicitly replaced in-memory transport. |

The framework also installs `fast-forward/fork` 1.0.1 by composition. This
sandbox does not start child workers or claim coverage of its process API.
Dependency installation does not mean every installed package is exercised.

The aggregate provider is not a web application or router. The small PSR-15
application and exact-path router belong to this repository. No APIs from
unfinished or unselected FastForward application, routing, persistence or
authentication packages are assumed.

Compare the release sources:
[framework bootstrap](https://github.com/php-fast-forward/framework/blob/v1.4.0/src/ServiceProvider/FrameworkServiceProvider.php),
[container function](https://github.com/php-fast-forward/container/blob/v1.6.0/src/functions.php),
[clock provider](https://github.com/php-fast-forward/clock/blob/v1.0.0/src/ServiceProvider/ClockServiceProvider.php),
[HTTP client provider](https://github.com/php-fast-forward/http-client/blob/v1.1.0/src/ServiceProvider/HttpClientServiceProvider.php).

## Follow each example

### CLI: shared service composition

Run `composer example`. `App\Console\Application` formats the report returned by
`RunExperiments`. That service reads the application name and sample items,
dispatches `ExperimentCompleted`, enumerates status values, chunks the items and
runs a short deferred scope. It creates a new event and trace for every call, so
repeated requests do not accumulate previous results.

The listener mutates only the event's in-memory trace. The deferred callbacks
append strings; they do not create files, acquire locks or contact services.
The UTC timestamp is the only changing value in the default example.

### HTTP: the existing class skeleton made executable

Run `composer example:http` to avoid opening a listening port. The script
injects `FrozenClock`, creates a PSR-7 server request through the registered
PSR-17 factory, calls `App\Web\Application::handle()` and prints the response.

The route handler uses `JsonResponder` to attach a `JsonStream` to the supplied
PSR-7 response. This preserves the response status and existing headers while
correctly encoding `false`, zero, empty strings, arrays and `null`. Invalid UTF-8
raises `JsonException` rather than silently substituting an empty object.

The application rebuilds its middleware chain per request, with the first
middleware passed to the constructor as the outermost. It does not dequeue a
shared mutable middleware list. The router handles GET-to-HEAD fallback,
explicit HEAD handlers, missing paths and method errors.

Run `composer serve` for the local front controller. It creates the request
from PHP globals and emits the returned status, headers and body.
Serve only `public/`; the project root includes development files.

### HTTP client: an explicit transport seam

Run `composer example:client`. The locked FastForward HTTP client provider
resolves Symfony's transport using the key `Symfony\Component\HttpClient\HttpClient::class`.
The example registers `MockHttpClient` under that key **before** the framework
provider, then resolves `Psr\Http\Client\ClientInterface` normally.

A fixture callback accepts one specific GET URL and returns an in-memory
`MockResponse`. The output's `transport_requests: 1` demonstrates that the
PSR-18 adapter used this transport. The example hostname is not contacted.

## Extend one experiment

1. Add a small class under `src/Experiment/` with explicit constructor dependencies.
2. Register its factory in `Bootstrap::create()` when another service factory uses it.
3. Add an exact route in `config/routes.php` for an HTTP exercise, or a focused
   script under `examples/` for a standalone exercise.
4. Inject a frozen clock and in-memory collaborators where appropriate.
5. Add a behavior test and document the command, expected output and limits.

A new external integration needs its own documented setup and opt-in execution.
Legacy files are reading material, not an autoload fallback.

## Dependency and runtime maintenance

The supported floor is PHP 8.3, matching the selected FastForward releases.
PHP 7.x, 8.0, 8.1 and 8.2 are unsupported. CI runs on 8.3, 8.4 and 8.5. The
platform setting controls dependency resolution; `composer check-platform-reqs`
checks the actual installed PHP and extensions.

Development uses PHPUnit directly. FastForward DevTools and its Composer
plugin workflow are not installed as part of this change. The sandbox's
commands remain explicit and independent of upstream development branches.

For a deliberate dependency update:

~~~sh
composer update --with-all-dependencies --no-plugins --no-scripts
composer check-platform-reqs
composer check
composer example
composer example:http
composer example:client
composer audit --locked
~~~

Review both manifests, the changed upstream APIs and this component table.
Commit the updated lock file together with affected examples and tests.
A successful native run is not a substitute for the full CI PHP matrix.
