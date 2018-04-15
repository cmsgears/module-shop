<?php
namespace cmsgears\shop\common\models\resources;

// Yii Imports
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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
 * ProductVariation provide options to alter primary product by features and discounts.
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
 * @property integer $icon
 * @property string $title
 * @property string $description
 * @property integer $order
 * @property integer $discountType
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property float $quantity
 * @property boolean $track
 * @property float $stock
 * @property float $sold
 * @property float $warn
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
class ProductVariation extends Entity implements IAuthor, IContent, IData, IGallery, IGridCache, ITemplate, IVisual {

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

	public static $typeMap = [
		self::TYPE_ADD_ON => 'Add On',
		self::TYPE_BASE => 'Base',
		self::TYPE_DISCOUNT => 'Discount',
		self::TYPE_QUANTITY => 'Quantity'
	];

	public static $discountTypeMap = [
		self::DISCOUNT_TYPE_FLAT => 'Flat Discount',
		self::DISCOUNT_TYPE_PERCENT => 'Percentage'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = ShopGlobal::TYPE_PRODUCT_VARIATION;

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
			[ [ 'productId', 'unitId', 'name', 'type', 'quantity' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Text Limit
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'type', 'discountType' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'price', 'discount', 'total', 'quantity', 'track', 'stock', 'sold', 'warn' ], 'number', 'min' => 0 ],
			[ [ 'active', 'gridCacheValid' ], 'boolean' ],
			[ 'unitId', 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'templateId', 'productId', 'addonId', 'bannerId', 'videoId', 'galleryId', 'unitId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate' ], 'date' ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End Date must be greater than or equal to Start Date.' ],
			[ 'startDate', 'validateStartDate' ],
			[ 'endDate', 'validateEndDate' ],
			[ 'addonId', 'validateAddon' ],
			[ 'discount', 'validateDiscount' ]
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
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE_UNIT ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT_UNIT ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'quantity' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'track' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_TRACK ),
			'stock' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_STOCK ),
			'sold' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_SOLD ),
			'warn' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_WARN ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'startDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_START ),
			'endDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_END ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
        ];
    }

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->unitId <= 0 ) {

				$this->unitId = null;
			}

			if( $this->order < 0 ) {

				$this->order = 0;
			}

			return true;
		}

		return false;
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
				$pStartDate = strtotime( $product->startDate );

				// Variation
				$startDate = strtotime( $this->startDate );

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
				$pEndDate = strtotime( $product->endDate );

				// Variation
				$endDate = strtotime( $this->endDate );

				if( $endDate > $pEndDate ) {

					$this->addError( 'endDate', 'Variation end date must be less than or equal to product end date.' );
				}
			}
		}
	}

	public function validateAddon( $attribute, $param ) {

		if( !$this->hasErrors() ) {

			if( isset( $this->addonId ) && $this->productId == $this->addonId ) {

				$this->addError( 'addonId', 'Addon Product and Variation Product cannot be same.' );
			}
		}
	}

	public function validateDiscount( $attribute, $param ) {

		if( !$this->hasErrors() && isset( $this->discount ) ) {

			$total = $this->getTotalPrice();

			if( $total < 0 ) {

				$this->addError( 'discount', 'Please update discount value to have valid price.' );
			}
		}
	}

	// ProductVariation ----------------------

	/**
	 * Returns unit associated with the variation.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'unitId' ] )->from( "$uomTable as uom" );
	}

	/**
	 * Returns primary product associated with the variation.
	 *
	 * @return \cmsgears\shop\common\models\entities\Product
	 */
	public function getProduct() {

		$productTable = Product::tableName();

		return $this->hasOne( Product::class, [ 'id' => 'productId' ] )->from( "$productTable as product" );
	}

	/**
	 * Returns addon product associated with the variation.
	 *
	 * @return \cmsgears\shop\common\models\entities\Product
	 */
	public function getAddon() {

		$productTable = Product::tableName();

		return $this->hasOne( Product::class, [ 'id' => 'addonId' ] )->from( "$productTable as addon" );
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

	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	public function getTrackStr() {

		return Yii::$app->formatter->asBoolean( $this->track );
	}

	public function getTotalPrice( $precision = 2 ) {

		$price		= $this->price;
		$discount	= $this->discount;

		if( $this->discountType == self::DISCOUNT_TYPE_PERCENT ) {

			$discount = ( ( $price * $discount ) / 100 );
		}

		$total = ( $price - $discount ) * $this->quantity;

		return round( $total, $precision );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ShopTables::getTableName( ShopTables::TABLE_PRODUCT_VARIATION );
	}

	// CMG parent classes --------------------

	// ProductVariation ----------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'template', 'product', 'addon', 'banner', 'video', 'gallery', 'unit', 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
