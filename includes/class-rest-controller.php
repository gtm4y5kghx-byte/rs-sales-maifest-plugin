<?php
/**
 * REST API controller for content manifest endpoint
 */

if (! defined('ABSPATH')) {
    exit;
}

class RS_Sales_REST_Controller
{

    private $namespace = 'rs-sales/v1';

    public function register_routes()
    {
        register_rest_route($this->namespace, '/content-manifest', [
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'get_manifest'],
                'permission_callback' => [$this, 'validate_api_key'],
            ],
            [
                'methods'             => 'OPTIONS',
                'callback'            => [$this, 'handle_preflight'],
                'permission_callback' => '__return_true',
            ],
        ]);
    }

    public function handle_preflight($request)
    {
        $response = new WP_REST_Response(null, 204);
        $this->add_cors_headers($response);
        return $response;
    }

    public function validate_api_key($request)
    {
        $api_key = $request->get_header('X-RS-API-Key');

        if (! defined('RS_SALES_API_KEY')) {
            return new WP_Error(
                'missing_config',
                'API key not configured on server',
                ['status' => 500]
            );
        }

        if ($api_key !== RS_SALES_API_KEY) {
            return new WP_Error(
                'unauthorized',
                'Invalid or missing API key',
                ['status' => 401]
            );
        }

        return true;
    }

    public function get_manifest($request)
    {
        $builder  = new RS_Sales_Manifest_Builder();
        $manifest = $builder->build();

        $response = new WP_REST_Response($manifest, 200);

        // Prevent WPEngine caching
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->add_cors_headers($response);

        return $response;
    }

    private function add_cors_headers($response)
    {
        if (defined('RS_SALES_CORS_ORIGIN')) {
            $response->header('Access-Control-Allow-Origin', RS_SALES_CORS_ORIGIN);
            $response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $response->header('Access-Control-Allow-Headers', 'X-RS-API-Key, Content-Type');
        }
    }
}
