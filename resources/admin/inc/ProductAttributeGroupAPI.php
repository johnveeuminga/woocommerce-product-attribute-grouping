<?php 
namespace WooCommerce\ProductAttributeGroup\Includes;

use Illuminate\Http\Request;
use Themosis\Facades\Action;
use Themosis\Facades\Input;
use WP_Error;
use WP_REST_Server;
use WooCommerce\ProductAttributeGroup\Models\ProductAttribute;
use WooCommerce\ProductAttributeGroup\Models\ProductAttributeGroup;
use WooCommerce\ProductAttributeGroup\Models\ProductAttributeGrouping;

class ProductAttributeGroupAPI extends \WP_REST_Controller{
	/**
	 * API namespace
	 *
	 * @var string
	 */
	protected $namespace = 'wc-product-attribute-group/v';

	/**
	 * Current API version
	 *
	 * @var string
	 */
	protected $api_version = '1';

	/**
	 * The base of the API
	 *
	 * @var string $api_base
	 */
	protected $api_base = 'product-attribute-group';

	/**
	 * Initializes a new API object
	 *
	 * @return void
	 */
	public function init(){
		Action::add('rest_api_init', [ $this, 'registerRestRoutes' ]);
	}

	public function registerRestRoutes(){
		$namespace = $this->namespace . $this->api_version;

		register_rest_route( $namespace, '/' . $this->api_base, [
			[
				'methods'	=> WP_REST_Server::READABLE,
				'callback'	=> [ $this, 'getAllProductAttributeGroups' ],
			]
		]);

		register_rest_route( $namespace, '/product-attributes', [
			[
				'methods'	=> WP_REST_Server::READABLE,
				'callback'	=> [ $this, 'getAllProductAttributeTaxonomy' ],
			]
		]);

		register_rest_route( $namespace, '/product-attribute-grouping', [
			[
				'methods'	=> WP_REST_Server::CREATABLE,
				'callback'	=> [ $this, 'createProductGrouping' ]
			]
		]);
	}

	/**
	 * Gets all possible product attribute groups
	 *
	 * @return array|boolean
	 */
	public function getAllProductAttributeGroups(){
		$terms = ProductAttributeGroup::all();
		$product_attribute_group = [];

		if(empty($terms)){
			return false;
		}

		foreach($terms as $term){
			$group = [];
			$group['group'] = $term;
			$group['attributes'] = [];
			$product_attribute_grouping = ProductAttributeGrouping::where('productattrgroup_id', $term->term_id)->orderBy('attr_order')->get();
			if($product_attribute_grouping){
				foreach($product_attribute_grouping as $attribute){
					$group['attributes'][] = get_taxonomy($attribute->productattr_name);
				}
			}
			$product_attribute_group[] = $group;
		}

		$attr_groups = ProductAttribute::all();
		$attr_groups_array = [];
		foreach($attr_groups as $attr){
			$taxonomy = get_taxonomy($attr->name);
			$product_attribute_grouping = ProductAttributeGrouping::where('productattr_name', $attr->name)->first();
			if(!$product_attribute_grouping){
				$attr_groups_array[] = $taxonomy;
			}
		}

		return json_encode(['groups' => $product_attribute_group, 'product_attributes' => $attr_groups_array]);
	}

	/**
	 * Gets all product attribute taxonomy
	 *
	 * @return string|boolean
	 */
	public function getAllProductAttributeTaxonomy(){
		$attr_groups = ProductAttribute::all();

		if(!$attr_groups){
			return false;
		}

		return json_encode($attr_groups);
	}

	/**
	 * Associates Product Grouping
	 *
	 * @return void
	 */
	public function createProductGrouping( $request ){
		$attribute_groups = json_decode(Input::get('productAttributeGroup'));
		foreach($attribute_groups as $attribute_group){
			$present_attribute_group = ProductAttributeGrouping::where('productattrgroup_id', $attribute_group->group->term_id)->get();
			$present_attribute_name = [];
			$present_attribute_group->each( function($item, $key) use (&$present_attribute_name){
				$present_attribute_name[] = $item->productattr_name;
			});
			$attr_array = [];
			foreach($attribute_group->attributes as $key=>$attribute){
				$product_grouping = ProductAttributeGrouping::where([
					['productattrgroup_id', $attribute_group->group->term_id],
					['productattr_name', $attribute->name]
				])->first();

				if(empty($product_grouping)){
					$new_product_grouping = new ProductAttributeGrouping();
					$new_product_grouping->productattrgroup_id = $attribute_group->group->term_id;
					$new_product_grouping->productattr_name = $attribute->name;
					$new_product_grouping->attr_order = $key;
					$new_product_grouping->save();
					$attr_array[] = $new_product_grouping->productattr_name;
				}else{
					$product_grouping->attr_order = $key; 
					$product_grouping->save();
					$attr_array[] = $product_grouping->productattr_name;
				}
			}

			foreach($present_attribute_name as $attribute){
				if(!in_array($attribute, $attr_array)){
					$product_grouping = ProductAttributeGrouping::where([
						['productattrgroup_id', $attribute_group->group->term_id],
						['productattr_name', $attribute]
					])->first();
					if($product_grouping){
						$product_grouping->delete();
					}
				}
			}
		}
	}

	/**
	 * Gets if user is permitted to do API calls
	 *
	 * @return boolean
	 */
	public function getAllProductAttributeGroupsPermission(){
		if(!current_user_can('edit_posts')){
			return new WP_Error( 'rest_forbidden', __("You don't have permissions to view this data.", 'WC_PAG'), ['status' => 401]);
		}

		return true;
	}
}