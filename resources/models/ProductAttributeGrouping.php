<?php 
namespace WooCommerce\ProductAttributeGroup\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeGrouping extends Model{
	/**
	* The table name
	*
	* @var string $table
	*/
	protected $table = 'productattributegroup';

	/**
	 * The primary key of the table
	 *
	 * @var string $primaryKey
	 */
	protected $primaryKey = 'productattrgrouping_id';

	/**
     * WordPress do not have "created_at" and "updated_at"
     * columns. Or define class const CREATED_AT and UPDATED_AT
     */
    public $timestamps = false;

}
