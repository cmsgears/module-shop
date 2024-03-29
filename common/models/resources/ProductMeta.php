<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\models\resources;

// CMG Imports
use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\entities\Product;

/**
 * The meta model used to store site meta data and attributes.
 *
 * @property integer $id
 * @property integer $modelId
 * @property string $icon
 * @property string $name
 * @property string $label
 * @property string $type
 * @property boolean $active
 * @property integer $order
 * @property string $valueType
 * @property string $value
 * @property string $data
 *
 * @since 1.0.0
 */
class ProductMeta extends \cmsgears\core\common\models\base\Meta {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ProductMeta ---------------------------

	/**
	 * Returns the site model using one-to-one(hasOne) relationship.
	 *
	 * @return \cmsgears\shop\common\models\entities\Product Product to which this meta belongs.
	 */
	public function getParent() {

		return $this->hasOne( Product::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ShopTables::getTableName( ShopTables::TABLE_PRODUCT_META );
	}

	// CMG parent classes --------------------

	// ProductMeta ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
