<?php
namespace cmsgears\shop\common\models\resources;

// CMG Imports
use cmsgears\shop\common\models\base\ShopTables;

/**
 * ProductVariation Resource
 *
 * @property integer $id
 * @property integer $modelId
 * @property string $name
 * @property string $type
 * @property integer $value
 * @property date $startDate
 * @property date $endDate
 * @property string $content
 * @property boolean $active
 */
class ProductVariation extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_FLAT		= 0;
	const TYPE_PERCENT	= 10;
	const TYPE_ADD_ON	= 20;

	// Public -----------------

	public static $typeMap	= [
			self::TYPE_FLAT => "Flat",
			self::TYPE_PERCENT => "Percent",
			self::TYPE_ADD_ON => "Add On"
	];

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

	/**
	 * @inheritdoc
	 */

	public function rules() {

		return [
				// Required, Safe
				[ [ 'name', 'quantity', 'type', 'value' ], 'required' ],
				[ [ 'startDate', 'endDate', 'description', 'active' ], 'safe' ],

				// Other
				[ [ 'active' ], 'number', 'min' => 0 ],
				[ [ 'startDate', 'endDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ProductVariation ----------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ShopTables::TABLE_PRODUCT_VARIATION;
	}

	// CMG parent classes --------------------

	// ProductVariation ----------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
