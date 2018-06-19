<?php 
namespace WooCommerce\ProductAttributeGroup\Models;

class ProductAttribute{

	/**
	 * Gets all the product attributes taxonomy.
	 *
	 * @return array|boolean
	 */
	public static function all(){
		$attributes = wc_get_attribute_taxonomies();

		if(!$attributes){
			return false;
		}

		$attributes_array = [];
		foreach($attributes as $attribute){
			$taxonomy = (get_taxonomy("pa_" . $attribute->attribute_name));
			$attributes_array[] = $taxonomy;
		}


		return $attributes_array;
	}
}