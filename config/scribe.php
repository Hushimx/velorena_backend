<?php

use Knuckles\Scribe\Extracting\Strategies;
use Knuckles\Scribe\Config\Defaults;
use Knuckles\Scribe\Config\AuthIn;
use function Knuckles\Scribe\Config\{removeStrategies, configureStrategy};

// Only the most common configs are shown. See the https://scribe.knuckles.wtf/laravel/reference/config for all.

return [
    /*
     * The HTML <title> for the generated documentation. If this is empty, Scribe will infer it from config('app.name').
     */
    'title' => 'Velorena API Documentation',

    /*
     * A short description of your API. Will be included in the docs webpage, Postman collection and OpenAPI spec.
     */
    'description' => 'API documentation for Velorena Backend - Authentication and User Management',

    /*
     * Tell Scribe what you want to be displayed in the introduction section. You can use Markdown.
     */
    'intro_text' => <<<INTRO
This documentation aims to provide all the information you need to work with our API.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
INTRO
    ,

    /*
     * Example requests for each endpoint will be shown using a JavaScript AJAX call.
     * You can disable this by setting this option to false.
     */
    'show_example_requests' => true,

    /*
     * Settings for `show_example_requests`:
     * - `headers`: Headers that will be added to the example requests
     * - `use_csrf`: Whether to include a CSRF token in the requests
     */
    'example_requests' => [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'use_csrf' => false,
    ],

    /*
     * Settings for the Postman collection generation.
     */
    'postman' => [
        'enabled' => true,
        'overrides' => [
            'info.version' => '1.0.0',
        ],
    ],

    /*
     * Settings for the OpenAPI spec generation.
     */
    'openapi' => [
        'enabled' => true,
        'overrides' => [
            'info.version' => '1.0.0',
        ],
    ],

    /*
     * How is your API authenticated? This information will be used in the displayed docs, generated examples and response calls.
     */
    'auth' => [
        'enabled' => true,
        'default' => false,
        'in' => 'bearer',
        'name' => 'Authorization',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{YOUR_AUTH_KEY}',
        'extra_info' => 'You can retrieve your token by making an authentication request to `/api/auth/login`.',
    ],

    /*
     * Routes for which documentation will be generated. You can use `*` wildcards to select all routes.
     */
    'routes' => [
        [
            'match' => [
                'domains' => ['*'],
                'prefixes' => ['api/*'],
                'versions' => ['v1'],
            ],
            'include' => [
                'api/auth/*',
                'api/profile',
            ],
            'exclude' => [
                // Exclude any routes you don't want to document
            ],
            'apply' => [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'response_calls' => [
                    'methods' => ['GET'],
                    'config' => [
                        'app.env' => 'documentation',
                    ],
                    'queryParams' => [
                        // Add any query parameters you want to include in the response calls
                    ],
                    'bodyParams' => [
                        // Add any body parameters you want to include in the response calls
                    ],
                ],
            ],
        ],
    ],

    /*
     * The type of documentation output to generate.
     * - "static" will generate a static HTMl page in the `docs` folder,
     * - "laravel" will generate the documentation as a Blade view,
     * so you can add routing and authentication.
     */
    'type' => 'static',

    /*
     * Settings for the static type output.
     */
    'static' => [
        /*
         * HTML documentation, assets and Postman collection will be generated to this folder.
         * Source Markdown will also be written to this folder.
         */
        'output_path' => 'public/docs',
    ],

    /*
     * Settings for the laravel type output.
     */
    'laravel' => [
        /*
         * Whether to automatically create a docs endpoint for you to view your generated docs.
         * If this is false, you can still set up routing manually.
         */
        'add_routes' => true,

        /*
         * URL path to use for the docs endpoint (if `add_routes` is true).
         * By default, `/docs` opens the HTML page, and `/docs.json` downloads the Postman collection.
         */
        'docs_url' => '/docs',

        /*
         * Middleware to apply to the docs endpoint (if `add_routes` is true).
         */
        'middleware' => [],
    ],

    /*
     * How should your API be grouped? You can use `*` wildcards to select all routes.
     */
    'groups' => [
        'default' => 'Endpoints',
        'auth' => 'Authentication',
        'profile' => 'User Profile',
    ],

    /*
     * Custom logo path. The logo will be copied from this location
     * during generate-time. Set this to false to use the default logo.
     *
     * If you're using the "laravel" type, set this to an absolute path
     * relative to your project root. E.g. `public/img/logo.png`.
     *
     * If you're using the "static" type, this will be ignored by default,
     * but if you add a `logo.png` file to the root of your generated docs folder,
     * that logo will be used. You can also manually edit the generated `index.html`
     */
    'logo' => false,

    /*
     * The last_updated, Powered by and other links can be shown in the docs.
     */
    'last_updated' => 'Last updated: {date}',

    'powered_by' => 'Powered by <a href="https://github.com/knuckleswtf/scribe">Scribe</a>',

    /*
     * The routes to be excluded from the documentation.
     */
    'excludes' => [
        // 'api/health',
    ],

    /*
     * Custom HTML to be included in the docs. You can use this to add custom CSS, JavaScript, or any other HTML.
     */
    'html' => [
        'extra_css' => [
            // 'css/custom.css',
        ],
        'extra_js' => [
            // 'js/custom.js',
        ],
        'body_js' => [
            // 'js/body.js',
        ],
        'head_html' => [
            // '<link rel="stylesheet" href="css/custom.css">',
        ],
        'body_html' => [
            // '<script src="js/custom.js"></script>',
        ],
    ],

    /*
     * The strategies Scribe will use to extract information about your routes at each stage.
     * Use configureStrategy() to specify settings for a strategy in the list.
     * Use removeStrategies() to remove an included strategy.
     */
    'strategies' => [
        'metadata' => [
            ...Defaults::METADATA_STRATEGIES,
        ],
        'headers' => [
            ...Defaults::HEADERS_STRATEGIES,
            Strategies\StaticData::withSettings(data: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]),
        ],
        'urlParameters' => [
            ...Defaults::URL_PARAMETERS_STRATEGIES,
        ],
        'queryParameters' => [
            ...Defaults::QUERY_PARAMETERS_STRATEGIES,
        ],
        'bodyParameters' => [
            ...Defaults::BODY_PARAMETERS_STRATEGIES,
        ],
        'responses' => configureStrategy(
            Defaults::RESPONSES_STRATEGIES,
            Strategies\Responses\ResponseCalls::withSettings(
                only: ['GET *'],
                // Recommended: disable debug mode in response calls to avoid error stack traces in responses
                config: [
                    'app.debug' => false,
                ]
            )
        ),
        'responseFields' => [
            ...Defaults::RESPONSE_FIELDS_STRATEGIES,
        ]
    ],

    /*
     * Configure custom responses for the example API calls to generate more realistic responses.
     * When an API call is made, Scribe will look through the database for similar responses and return one.
     */
    'faker_seed' => null,

    /*
     * If you would like the package to generate the same example values for parameters on each run,
     * set this to any number (eg. 1234)
     */
    'examples' => [
        'faker_seed' => null,

        /*
         * With this option, if you have an example, the documentation will not generate a random value,
         * but use the example, so you can have all fields have similar, coherent data.
         * You can also override this for specific parameters on specific endpoints
         */
        'models_source' => ['factoryCreate', 'factoryMake', 'factoryFirst', 'factoryFirstOrFail'],
    ],

    /*
     * The source URLs for the examples. When `examples.models_source` is set to 'factoryCreate' or 'factoryMake',
     * this is used to refresh the database and run the factory.
     * Should be the URLs of the APIs you are documenting (e.g. https://myapp.com/api, or http://localhost:3000).
     *
     * If this is null, Scribe will use your app config to get the URL (from app.url or app.env('APP_URL'))
     */
    'base_url' => null,
];
