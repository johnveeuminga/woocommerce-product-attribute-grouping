<?php 
namespace WooCommerce\ProductAttributeGroup\Models;

class ProductAttributeGroup{
	/**
	 * The taxonomy name of the product attribute group
	 * 
	 * @var string $product_attr_group_tax
	 */
	protected static $product_attr_group_tax = 'product_attribute_group';
	/**
	 * Gets all the product attribute groups
	 *
	 * @return array|boolean 
	 */
	public static function all(){
		$terms = get_terms([
			'taxonomy' => 'product_attribute_groups',
			'hide_empty' => false
		]);

		if(is_wp_error($terms) || empty($terms)){
			return false;
		}

		return $terms;
	}

}