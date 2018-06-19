<?php
use WooCommerce\ProductAttributeGroup\Includes\ProductAttributeGroup;
use WooCommerce\ProductAttributeGroup\Includes\ProductAttributeGroupAPI;

$product_attribute_group = new ProductAttributeGroup();
$product_attribute_group->init();

$product_attribute_group_api = new ProductAttributeGroupAPI();
$product_attribute_group_api->init();