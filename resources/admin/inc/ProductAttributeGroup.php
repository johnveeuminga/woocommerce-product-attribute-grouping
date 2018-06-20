<?php 
namespace WooCommerce\ProductAttributeGroup\Includes;

use Themosis\Facades\Action;
use Themosis\Facades\Asset;
use Themosis\Facades\Page;
use Themosis\Facades\Taxonomy;
use Themosis\Facades\View;
use WooCommerce\ProductAttributeGroup\Models\ProductAttribute;
use WooCommerce\ProductAttributeGroup\Models\ProductAttributeGroup as ProductAttributeGroupModel;

class ProductAttributeGroup{
	/**
	 * The table name.
	 * 
	 * @var string $table_name
	 */
	protected $table_name = 'productattributegroup';

	/**
	 * The Product Attribute Group Taxonomy
	 *
	 * @var string $product_att_grp_tax_name
	 */
	public $groups;

	/**
	 * The Product Attribute Group Taxonomy
	 *
	 * @var string $product_att_grp_tax_name
	 */
	protected $product_attr_grp_tax_name = 'product_attribute_groups';

	/**
	 * The product attribute page
	 *
	 * @var string $group_attribute_page
	 */
	protected $group_attribute_page_hook = 'product_page_group-product-attributes';

	/**
	 * Initializes ProductAttributeGroup
	 *
	 * @return void
	 */
	public function init(){
		
		Action::add('init', $this->registerProductAttributeTaxonomy(), 10);
		Action::add('admin_menu', $this->createProductGroupingPage(), 20);
		Action::add('admin_enqueue_scripts', [$this, 'enqueueAssets']);
	}

	/**
	 * Add register activation hook
	 *
	 * @return void
	 */
	public function registerHook($file){
		register_activation_hook($file, [$this, 'createProductAttributeTable']);
	}

	/**
	 * Add deactivation hook
	 *
	 * @return void
	 */
	public function registerDeactivationHook($file){
		register_deactivation_hook($file, [$this, 'deleteProductAttributeGroupTable']);
	}

	/**
	 * Registers the product attribute group taxonomy
	 */
	private function registerProductAttributeTaxonomy(){
		$tax = Taxonomy::make($this->product_attr_grp_tax_name, 'product', 'Attribute Groups', 'Attribute Group')->set([
			'labels' => [
				'name' => 'Attribute Groups',
				'singular_name' => 'Attribute Group',
				'menu_name' => 'Attribute Groups',
				'all_items' => 'All Attribute Groups',
				'edit_item' => 'Edit Attribute Group',
				'view_item' => 'View Attribute Group',
				'update_item' => 'Update Attribute Group',
				'add_new_item' => 'Add Attribute Group',
				'new_item_name' => 'New Attribute Group'
			],
			'public' => true,
			'rewrite' => [
				'slug' => 'product-attribute-group'
			],
			'hierarchical' => true,
			'query_var' => true
		]);
		$tax->bind();
	}

	/**
	 * Creates a custom setting page for the product attribute groups
	 *
	 * @return void
	 */
	private function createProductGroupingPage(){
		$page_view = View::make('woocommerce-product-attribute-group.group-product-attributes', []);
		Page::make('group-product-attributes', 'Group Product Attributes', 'edit.php?post_type=product', $page_view)->set();
	}

	/**
	 * Enqueues the admin scripts and styles
	 *
	 * @return void
	 */
	public function enqueueAssets($hook){
		if($hook !== $this->group_attribute_page_hook){
			return;
		}
		wp_enqueue_script('product-group-attributes-main', plugins_url('woocommerce-product-attribute-group') . '/dist/js/build.js', [], '1.0', true);
		wp_enqueue_style('product-group-attributes-main', plugins_url('woocommerce-product-attribute-group') . '/dist/css/style.css');
	}


	/**
	 * Creates a new table for the taxonomy grouping
	 *
	 * @return void
	 */
	public function createProductAttributeTable(){
	    global $wpdb;

	    $table_name = $wpdb->prefix . $this->table_name;

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
	    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
	        $charset_collate = $wpdb->get_charset_collate();
	        $sql = "CREATE TABLE $table_name(
	        	productattrgrouping_id mediumint(9),
	            productattrgroup_id mediumint(9),
	            productattr_name VARCHAR(255),
	            attr_order mediumint(9) DEFAULT 0,
	            PRIMARY KEY (productattrgrouping_id) 
	        ) $charset_collate;";

	        dbDelta($sql);
	    }
	}

	/**
	 * Deletes the table for product attribute group
	 *
	 * @return void
	 */
	public function deleteProductAttributeGroupTable(){
		global $wpdb;

		$table_name = $wpdb->prefix . $this->table_name;

	    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
	    	$sql = "DROP TABLE $table_name";
	    	$this->performSQL();
		}
	}

	/**
	 * Performs an SQL
	 *
	 * @return void
	 */
	private function performSQL($sql){
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}