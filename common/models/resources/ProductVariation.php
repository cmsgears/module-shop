<?php
namespace cmsgears\shop\common\models\resources;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\entities\Product;

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
				[ [ 'startDate', 'endDate', 'content', 'active' ], 'safe' ],

				// Other
				[ [ 'active' ], 'number', 'min' => 0 ],
				[ [ 'startDate', 'endDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ],
				[ [ 'startDate', 'endDate' ], 'validateDate' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	public function validateDate( $attribute, $param ) {

		$product	= $this->product;

		if( isset( $product->startDate ) || isset( $product->endDate ) ) {

			// Will consider product endDate as 10 years if no end date were mentioned by creator.
			$tenYears	= date( 'Y-m-d', strtotime("+10 years" ) );

			// Product
			$pStartDate	= strtotime( $product->startDate );
			$pEndDate	= isset( $product->endDate ) ? strtotime( $product->endDate ) : strtotime( $tenYears );

			// Variation
			$startDate	= strtotime( $this->startDate );
			$endDate	= strtotime( $this->endDate );

			if( $startDate < $pStartDate || $endDate > $pEndDate || $endDate < $startDate ) {

				$this->addError('startDate','Please provide correct Start Date');

				$this->addError('endDate','Please provide correct End Date');
			}
		}
	}

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

	public function getTypeStr() {

		return self::$typeMap[ $this->type ];
	}

	public function getProduct() {

		return $this->hasOne( Product::className(), [ 'id' => 'modelId' ] );
	}

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
