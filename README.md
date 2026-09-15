# PHP Sandbox

A small place to experiment with PHP classes, dependency injection and HTTP
application structure. The original 2018 code explored Slim 3, actions,
responders, middleware, ORM entities and WebSockets. This revision completes the
existing application, router, action, responder and console skeleton using
released [PHP FastForward](https://github.com/php-fast-forward) components.

The active examples run locally with in-memory data. They need no accounts,
database, message broker, credentials or external API.

## Requirements and installation

- PHP **8.3 or newer**, with `mbstring`.
- Composer 2.
- For tests: PHP's DOM, XML, XMLWriter and tokenizer extensions.

From the repository root:

~~~sh
composer install --no-interaction --prefer-dist --no-plugins --no-scripts
composer check-platform-reqs
composer check
~~~

Commit `composer.lock` when intentionally updating dependencies. It fixes the
application's complete dependency graph. Composer resolves against PHP 8.3 so an
update performed on a newer PHP does not silently raise the minimum version.
Plugins are disabled and installation does not replace Git hooks.

## Run the examples

~~~sh
composer example
composer example:http
composer example:client
~~~

| Command | What it demonstrates |
| --- | --- |
| `composer example` | Shared container, dot-notation configuration, PSR-20 clock, enum values, chunked iteration, a typed PSR-14 event and deferred callbacks. |
| `composer example:http` | The same experiment through a PSR-7 request and the PSR-15 application, entirely in memory, with a frozen clock. |
| `composer example:client` | A PSR-18 client built by FastForward with a simulated Symfony transport; the fixture never contacts its example URL. |

The CLI uses the current UTC time. The in-memory HTTP example always uses
`2026-01-01T12:00:00+00:00`. Its response has status `200`, JSON content, three
chunks (`alpha/beta`, `gamma/delta`, `epsilon`) and these traces:

~~~json
{
  "event_trace": ["Observed PHP FastForward sandbox"],
  "cleanup_trace": ["scope body", "second registered", "first registered"]
}
~~~

The client example returns:

~~~json
{
  "status": 200,
  "body": {"source": "in-memory fixture"},
  "transport_requests": 1
}
~~~

The scripts also work directly as `php bin/app`, `php examples/http.php` and
`php examples/http-client.php`; they resolve paths relative to their own files.

## Try the local HTTP server

~~~sh
composer serve
~~~

Open [http://127.0.0.1:8080/](http://127.0.0.1:8080/) or use a second terminal:

~~~sh
curl -i http://127.0.0.1:8080/experiments
curl -I http://127.0.0.1:8080/experiments
curl -i -X POST http://127.0.0.1:8080/experiments
curl -i http://127.0.0.1:8080/missing
~~~

`GET /` and `GET /experiments` return the experiment report. `HEAD` returns the
same status and headers without a body. Other methods on known paths return
`405` with `Allow: GET, HEAD`; unknown paths return `404`. Query strings do not
change route matching. Stop the development server with **Ctrl+C**.

If port 8080 is occupied, choose another loopback port, for example:

~~~sh
php -S 127.0.0.1:18083 -t public public/index.php
~~~

This server binds only to loopback. It is PHP's development server, not a
production deployment configuration. GitHub Pages does not execute this PHP
application.

## Project guide

- [`src/Bootstrap.php`](src/Bootstrap.php) assembles application factories and the
  FastForward framework provider. A supplied PSR-20 clock overrides the default.
- [`config/settings.php`](config/settings.php) contains sample data, UTC timezone
  and the typed event listener registration.
- [`config/routes.php`](config/routes.php) maps exact paths to request handlers.
- [`src/Experiment/`](src/Experiment/) contains the experiment shared by HTTP and CLI.
- [`src/Web/`](src/Web/) completes the original application/action/responder design.
- [`examples/`](examples/) contains the in-memory server and client walkthroughs.
- [`tests/`](tests/) checks released dependencies, route behavior, middleware order,
  JSON values and entrypoints launched from another working directory.
- [`docs/integration.md`](docs/integration.md) explains the component map,
  extension points, compatibility and upgrade workflow.
- [`legacy/README.md`](legacy/README.md) identifies four original class specimens
  preserved for reading outside the application autoloader.

## Compatibility and verification

The local suite passed with the checked-in lock file on:

| PHP | Environment | Result |
| --- | --- | --- |
| 8.3.33 | Linux / Alpine, no network, read-only source mount | 23 tests, 111 assertions |
| 8.4.25 | Linux / Alpine, no network, read-only source mount | 23 tests, 111 assertions |
| 8.5.10 | Linux / Alpine, no network, read-only source mount | 23 tests, 111 assertions |
| 8.5.10 | macOS CLI | 23 tests, 111 assertions |

[CI](.github/workflows/tests.yml) checks PHP 8.3, 8.4 and 8.5, Composer metadata,
active PHP syntax, the test suite and dependency advisories. `composer check`
runs the deterministic checks locally; `composer audit --locked` checks the
current advisory database and needs network access.

## Migration from the old sandbox

PHP 7.2 / Slim 3 and the old service configuration are no longer the active
runtime. The original `App\Web\Application`, `Router`, `Action\IndexAction`,
`Responder\JsonResponder` and `App\Console\Application` names now have tested
implementations. This is an application refresh, not a compatibility release
for consumers of those unfinished classes.

The unconfigured authentication, database, queue, Redis, cloud deployment and
automatic Git-hook experiments were removed from the active tree. Their exact
source remains available in the
[original revision](https://github.com/coisa/php-sandbox/tree/98ed9a446e9f51f3f9d20137a7d6ffd374894061).
Only the small entity and broadcast specimens described under `legacy/` remain
as reading material.

The current router intentionally supports exact paths and methods. There is no
parameterized routing, authentication, persistence, template engine, worker
runtime or production error handler. FastForward supplies infrastructure
services; this repository supplies the demonstration application around them.

## License

[Apache License 2.0](LICENSE), preserving the original sandbox license.
Dependencies retain their respective licenses.
