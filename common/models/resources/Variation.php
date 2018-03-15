<?php
namespace cmsgears\shop\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\ITemplate;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\IGallery;

use cmsgears\core\common\models\base\Entity;
use cmsgears\cart\common\models\resources\Uom;
use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\entities\Product;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\GalleryTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Variation provide options to alter primary product by features and discounts.
 *
 * @property integer $id
 * @property integer $templateId
 * @property integer $productId
 * @property integer $addonId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $galleryId
 * @property integer $unitId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property integer $type
 * @property string $title
 * @property string $description
 * @property integer $discountType
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property float $quantity
 * @property float $sold
 * @property boolean $active
 * @property date $startDate
 * @property date $endDate
 * @property datetime $createdAt
 * @property datetime $modifedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Variation extends Entity implements IAuthor, IContent, IData, IGallery, IGridCache, ITemplate, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * The add-on variation adds additional items that can be purchased together with primary
	 * product. In most of the cases these additional items can be accessories of primary product
	 * and adds additional cost at discounted rates. Stock must be maintained at variation level.
	 */
	const TYPE_ADD_ON	=   0;

	/**
	 * The base variation alters the primary product in look and feel. It might also provide
	 * altered product with slight difference in core features. It does not add additional price,
	 * but make changes in the primary product price. Stock must be maintained at variation level.
	 */
	const TYPE_BASE		= 200;

	/**
	 * The discount variation adds additional discount on top of product discount. It might last
	 * for shorter duration in order to boost sales or clearance sale. It will also be applied on
	 * Add On and Base variations. This type of variation does not need stock maintenance.
	 */
	const TYPE_DISCOUNT	= 400;

	/**
	 * The quantity variation adds discount on bulk purchase. Similar to discount variation, it
	 * will also be applied on Add On and Base variations and does not need stock maintenance.
	 */
	const TYPE_QUANTITY	= 600;

	const DISCOUNT_TYPE_FLAT	=   0;
	const DISCOUNT_TYPE_PERCENT	= 200;

	// Public -----------------

	public static $typeMap	= [
		self::TYPE_ADD_ON => 'Add On',
		self::TYPE_BASE => 'Base',
		self::TYPE_DISCOUNT => 'Discount',
		self::TYPE_QUANTITY => 'Quantity'
	];

	public static $discountTypeMap	= [
		self::DISCOUNT_TYPE_FLAT => 'Flat Discount',
		self::DISCOUNT_TYPE_PERCENT => 'Percentage'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use AuthorTrait;
	use ContentTrait;
    use DataTrait;
	use GalleryTrait;
	use GridCacheTrait;
	use TemplateTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'authorBehavior' => [
                'class' => AuthorBehavior::class
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */

	public function rules() {

        // Model Rules
        $rules = [
			// Required, Safe
			[ [ 'productId', 'name', 'type' ], 'required' ],
			[ [ 'startDate', 'endDate', 'content', 'active' ], 'safe' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Text Limit
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'type', 'discountType' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'price', 'discount', 'total', 'quantity', 'sold' ], 'number', 'min' => 0 ],
			[ [ 'active', 'gridCacheValid' ], 'boolean' ],
			[ [ 'templateId', 'productId', 'addonId', 'bannerId', 'videoId', 'galleryId', 'unitId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate' ], 'date' ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End Date must be greater than or equal to Start Date.' ],
			[ 'startDate', 'validateStartDate' ],
			[ 'endDate', 'validateEndDate' ]
		];

		// Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'title', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'productId' => Yii::$app->shopMessage->getMessage( ShopGlobal::FIELD_PRODUCT ),
			'addonId' => Yii::$app->shopMessage->getMessage( ShopGlobal::FIELD_ADDON_PRODUCT ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'videoId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIDEO ),
			'galleryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GALLERY ),
			'unitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_QUANTITY ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
            'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'discountType' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT_TYPE ),
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'quantity' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'sold' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_SOLD ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'startDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_START ),
			'endDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_END ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	/**
	 * Check whether variation start date is greater than product start date.
	 *
	 * @param string $attribute
	 * @param string $param
	 */
	public function validateStartDate( $attribute, $param ) {

		if( !$this->hasErrors() ) {

			$product = $this->product;

			if( isset( $product->startDate ) && isset( $this->startDate ) ) {

				// Product
				$pStartDate	= strtotime( $product->startDate );

				// Variation
				$startDate	= strtotime( $this->startDate );

				if( $startDate < $pStartDate ) {

					$this->addError( 'startDate', 'Variation start date must be greater than or equal to product start date.' );
				}
			}
		}
	}

	/**
	 * Check whether variation end date is less than product end date.
	 *
	 * @param string $attribute
	 * @param string $param
	 */
	public function validateEndDate( $attribute, $param ) {

		if( !$this->hasErrors() ) {

			$product = $this->product;

			if( isset( $product->endDate ) && isset( $this->endDate ) ) {

				// Product
				$pEndDate	= strtotime( $product->endDate );

				// Variation
				$endDate	= strtotime( $this->endDate );

				if( $endDate > $pEndDate ) {

					$this->addError( 'endDate', 'Variation end date must be less than or equal to product end date.' );
				}
			}
		}
	}

	// Variation -----------------------------

	/**
	 * Returns unit associated with the variation.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getUnit() {

		return $this->hasOne( Uom::class, [ 'id' => 'unitId' ] );
	}

	/**
	 * Returns primary product associated with the variation.
	 *
	 * @return \cmsgears\shop\common\models\entities\Product
	 */
	public function getProduct() {

		return $this->hasOne( Product::class, [ 'id' => 'productId' ] );
	}

	/**
	 * Returns addon product associated with the variation.
	 *
	 * @return \cmsgears\shop\common\models\entities\Product
	 */
	public function getAddonProduct() {

		return $this->hasOne( Product::class, [ 'id' => 'addonId' ] );
	}

	/**
	 * Returns string representation of type.
	 *
	 * @return string
	 */
	public function getTypeStr() {

		return self::$typeMap[ $this->type ];
	}

	/**
	 * Returns string representation of discount type.
	 *
	 * @return string
	 */
	public function getDiscountTypeStr() {

		return self::$typeMap[ $this->discountType ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ShopTables::getTableName( ShopTables::TABLE_VARIATION );
	}

	// CMG parent classes --------------------

	// Variation -----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'product', 'addon', 'banner', 'gallery', 'unit', 'creator' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
