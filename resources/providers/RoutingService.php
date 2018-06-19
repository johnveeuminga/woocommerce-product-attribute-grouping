<?php

namespace WooCommerce\ProductAttributeGroup\Services;

use Themosis\Facades\Route;
use Themosis\Foundation\ServiceProvider;

class RoutingService extends ServiceProvider
{
    /**
     * Register plugin routes.
     * Define a custom namespace.
     */
    public function register()
    {
        Route::group([
            'namespace' => 'WooCommerce\ProductAttributeGroup\Controllers'
        ], function () {
            require themosis_path('plugin.woocommerce-product-attribute-group.resources').'routes.php';
        });
    }
}