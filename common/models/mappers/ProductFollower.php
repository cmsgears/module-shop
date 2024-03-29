<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\models\mappers;

// CMG Imports
use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\entities\Product;

/**
 * ProductFollower represents interest of user in page or post.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property integer $type
 * @property integer $order
 * @property boolean $active
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property integer $createdAt
 * @property integer $modifiedAt
 * @property string data
 *
 * @since 1.0.0
 */
class ProductFollower extends \cmsgears\core\common\models\base\Follower {

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

	// PageFollower --------------------------

	/**
	 * Return corresponding product.
	 *
	 * @return \cmsgears\shop\common\models\entities\Product
	 */
	public function getParent() {

		return $this->hasOne( Product::class, [ 'id' => 'parentId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return ShopTables::getTableName( ShopTables::TABLE_PRODUCT_FOLLOWER );
	}

	// CMG parent classes --------------------

	// PageFollower --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
