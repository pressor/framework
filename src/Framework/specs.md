# Functional Specs

## Disclaimer

This spec is a living document and should not be complete.

## Scenarios

1. Client requests a `non-wordpress route`. Laravel handles request and returns response

1. Client requests a `direct wordpress route` (login or admin page). `wp-config.php` instantiates laravel app and registeres pressor and its components (path, proxy, hooks, request, options, plugins).

1. Client requests a `fall-through route` not captured by laravel. We'll assume this should be handled by pressor and passed to wordpress. Laravel will register pressor and its components and then pass route to controller.

## Technical Specs

1. `non-wordpress route`: we can ignore this.

1. `direct-wordpress route`: pressor and components are registered. plugins are registered and bound if the request context (admin, ajax) requires plugin to be bound. wordpress does its thing and then all the bound callbacks are fired.

1. `fall-through route`: pressor and components are registered. Controller passes request to bus. Bus registers and binds plugins, then passes off to another handler to require the `wp-blog-header.php`. output buffer is captured and returned.

## Pressor instantiation

1. User hits `direct-wordpress route`. autoloading makes the Laravel app available and registers pressor. (Load some middleware so we can do some filtering stuff on these routes?)

1. User hits `fall-through route`. As we're coming through `public/index.php`, Laravel will have been instantiated by this time and have registered pressor.

1. By this time, `pressor.registry` will be registered on container with its optional configure providers.

1. Wordpress goes through its `mu_plugins` and finds our mu_plugin which specifically calls `$pressor->boot()`.

1. pressor `boot()` will `pressor.hooks->registerBaseCallbacks()` to bind the base hooks so we can mark events as complete.

1. pressor `boot()` binds its own `bind()` method to wordpress `plugins_loaded` event.

1. pressor `boot()` will call `pressor.registry->bootstrap()`.

1. registry `bootstrap()` will then spin it's configured plugin providers and calls its `register()` method to register plugin on container. The `register()` method allows developer to construct plugin and set its configuration.

1. Within default wordpress plugin lifecycle, developer can register additional plugin providers using `pressor.registry->plugin()`.

1. When container makes plugin, the plugin's provider will also bind plugin instance's `boot()` method to a wordpress event.

1. Until plugin boots, developer or other plugins can interact with plugin using the `configure()` method.

1. Once plugin `boot()` method is called any additional `configure()` or `boot()` calls will throw `LogicException`

1. Eventually wordpress fires `plugins_loaded` which calls `pressor->bind()`.

1. `pressor->bind()` calls `pressor.registry->bind()`, causing registry to spin through the registered plugins again and calls `shouldLoadOnRequest()` with the `pressor.request`. This allows plugin developer to determine if the plugin should load in a given wordpress request context (admin, client, ajax).

1. If plugin provider returns `true` on `shouldLoadOnRequest()`, `pressor.registry` will `bootPlugin()` on provider.

1. `pressor->bind()` now calls `pressor.hooks->bind()` binding all the registered hooks to wordpress.
