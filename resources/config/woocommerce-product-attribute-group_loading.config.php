<?php

/**
 * Plugin autoloading configuration.
 */
$resource_path = themosis_path('plugin.woocommerce-product-attribute-group.resources');

return [
	'WooCommerce\\ProductAttributeGroup\\Includes\\' => $resource_path . 'admin/inc', 
    'WooCommerce\\ProductAttributeGroup\\Services\\' => $resource_path . 'providers',
    'WooCommerce\\ProductAttributeGroup\\Controllers\\' => $resource_path . 'controllers',
    'WooCommerce\\ProductAttributeGroup\\Models\\' => $resource_path . 'models',
];