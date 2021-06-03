<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\interfaces\base\IApproval;
use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\base\IVisibility;
use cmsgears\core\common\models\interfaces\resources\IComment;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IMeta;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\ICategory;
use cmsgears\core\common\models\interfaces\mappers\IFile;
use cmsgears\core\common\models\interfaces\mappers\IFollower;
use cmsgears\core\common\models\interfaces\mappers\IOption;
use cmsgears\core\common\models\interfaces\mappers\ITag;
use cmsgears\cms\common\models\interfaces\resources\IPageContent;
use cmsgears\cms\common\models\interfaces\mappers\IBlock;
use cmsgears\cms\common\models\interfaces\mappers\IElement;
use cmsgears\cms\common\models\interfaces\mappers\IWidget;

use cmsgears\cart\common\models\resources\Uom;
use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\resources\ProductMeta;
use cmsgears\shop\common\models\resources\ProductVariation;
use cmsgears\shop\common\models\mappers\ProductFollower;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\CommentTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\mappers\FollowerTrait;
use cmsgears\core\common\models\traits\mappers\OptionTrait;
use cmsgears\core\common\models\traits\mappers\TagTrait;
use cmsgears\cms\common\models\traits\resources\PageContentTrait;
use cmsgears\cms\common\models\traits\mappers\BlockTrait;
use cmsgears\cms\common\models\traits\mappers\ElementTrait;
use cmsgears\cms\common\models\traits\mappers\WidgetTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\utilities\DateUtil;

/**
 * Product model represents product to be sold by shop.
 *
 * It supports different type of unit of measurement to avoid run time conversions. These includes:
 * ** Primary Unit - The base unit which can be used to identify the product unit at packaging level.
 * ** Purchasing Unit - The purchasing unit is the main unit used for shopping and tracking purposes.
 * It can be same or different from Primary Unit. In ideal cases, it must be equal or greater than the
 * Primary Unit i.e. the packaging is same as that of Primary Unit or several primary units are packed
 * in larger packages.
 * ** Quantity Unit - The quantity unit used to identify item count in Primary Unit.
 * ** Weight Unit - The weight unit used to identity the weight of Quantity Unit.
 * ** Volume Unit - The volume unit used to identity the volume of Quantity Unit.
 * ** Length Unit - The length unit used to identity the dimensions of Quantity Unit.
 *
 * The fields price, discount and total directly relates to Purchasing Unit.
 *
 * The field primary directly relates to Primary Unit.
 * The field purchase directly relates to Purchasing Unit.
 * The field quantity directly relates to Quantity Unit.
 * The field weight directly relates to Weight Unit.
 * The field volume directly relates to Volume Unit.
 * The fields length, width, height, radius directly relates to Length Unit.
 *
 * The flag inventory will be used to identity whether stock/inventory information is required for the product.
 *
 * The fields inventory, sold and warn directly relates to Purchasing Unit and used to track product
 * stock if inventory is true.
 *
 * The shop flag identify whether the product is available for sales.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $avatarId
 * @property integer $primaryUnitId
 * @property integer $purchasingUnitId
 * @property integer $quantityUnitId
 * @property integer $weightUnitId
 * @property integer $volumeUnitId
 * @property integer $lengthUnitId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $texture
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $visibility
 * @property integer $order
 * @property string $sku
 * @property string $code
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property float $primary
 * @property float $purchase
 * @property float $quantity
 * @property float $weight
 * @property float $volume
 * @property float $length
 * @property float $width
 * @property float $height
 * @property float $radius
 * @property boolean $inventory
 * @property float $stock
 * @property float $sold
 * @property float $warn
 * @property boolean $cart
 * @property boolean $shop
 * @property string $shopNotes
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property boolean $reviews
 * @property datetime $startDate
 * @property datetime $endDate
 * @property datetime $createdAt
 * @property datetime $modifedAt
 * @property string $shopNotes
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Product extends \cmsgears\core\common\models\base\Entity implements IApproval, IAuthor, IBlock,
	ICategory, IComment, IContent, IData, IElement, IFeatured, IFile, IFollower, IGridCache, IMeta,
	IMultiSite, INameType, IOption, IOwner, IPageContent, ISlugType, ITag, IVisibility, IVisual, IWidget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = ShopGlobal::TYPE_PRODUCT;

	protected $followerClass;

	protected $metaClass;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ApprovalTrait;
    use AuthorTrait;
	use BlockTrait;
	use CategoryTrait;
	use CommentTrait;
	use ContentTrait;
    use DataTrait;
	use ElementTrait;
	use FeaturedTrait;
	use FileTrait;
	use FollowerTrait;
	use GridCacheTrait;
	use MetaTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use OptionTrait;
	use OwnerTrait;
	use PageContentTrait;
	use SlugTypeTrait;
	use TagTrait;
	use VisibilityTrait;
	use VisualTrait;
	use WidgetTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( $config = [] ) {

		$this->followerClass = ProductFollower::class;

		$this->metaClass = ProductMeta::class;

		parent::__construct();
	}

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
            ],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for Site Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'slug' ] ]
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
			[ [ 'name' ], 'required' ],
			[ [ 'purchasingUnitId', 'price', 'purchase' ], 'required', 'on' => 'shop' ],
			[ [ 'id', 'content', 'shopNotes' ], 'safe' ],
			// Unique
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'icon', 'texture' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'slug', 'sku', 'code' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'status', 'visibility', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'price', 'discount', 'primary', 'purchase', 'quantity', 'total', 'weight', 'volume', 'length', 'width', 'height', 'radius', 'stock', 'sold', 'warn' ], 'number', 'min' => 0 ],
			[ [ 'cart', 'shop', 'inventory', 'pinned', 'featured', 'popular', 'reviews', 'gridCacheValid' ], 'boolean' ],
			[ [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'userId', 'avatarId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate' ], 'date' ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End Date must be greater than or equal to Start Date.' ],
			[ 'inventory', 'validateInventory' ]
        ];

		// Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'title', 'description', 'shopNotes' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'primaryUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_PRIMARY ),
			'purchasingUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_PURCHASING ),
			'quantityUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_QUANTITY ),
			'weightUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_WEIGHT ),
			'volumeUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_VOLUME ),
			'lengthUnitId' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_UNIT_LENGTH ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OWNER ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'texture' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEXTURE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
            'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'sku' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_SKU ),
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE_UNIT ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT_UNIT ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'primary' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QTY_PRIMARY ),
			'purchase' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QTY_PURCHASE ),
			'quantity' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY ),
			'weight' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WEIGHT ),
			'volume' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_VOLUME ),
			'length' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_LENGTH ),
			'width' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_WIDTH ),
			'height' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_HEIGHT ),
			'inventory' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_INVENTORY ),
			'stock' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_STOCK ),
			'sold' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_SOLD ),
			'warn' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_WARN ),
			'cart' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CART ),
			'shop' => Yii::$app->shopMessage->getMessage( ShopGlobal::FIELD_SHOP ),
			'shopNotes' => 'Shop Notes',
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'popular' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_POPULAR ),
			'reviews' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REVIEWS ),
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

			// Default User
			if( empty( $this->userId ) || $this->userId <= 0 ) {

				$this->userId = null;
			}

			if( empty( $this->primaryUnitId ) || $this->primaryUnitId <= 0 ) {

				$this->primaryUnitId = null;
			}

			if( empty( $this->purchasingUnitId ) || $this->purchasingUnitId <= 0 ) {

				$this->purchasingUnitId = null;
			}

			if( empty( $this->quantityUnitId ) || $this->quantityUnitId <= 0 ) {

				$this->quantityUnitId = null;
			}

			if( empty( $this->weightUnitId ) || $this->weightUnitId <= 0 ) {

				$this->weightUnitId = null;
			}

			if( empty( $this->volumeUnitId ) || $this->volumeUnitId <= 0 ) {

				$this->volumeUnitId = null;
			}

			if( empty( $this->lengthUnitId ) || $this->lengthUnitId <= 0 ) {

				$this->lengthUnitId = null;
			}

			// Default Status - New
			if( empty( $this->status ) || $this->status <= 0 ) {

				$this->status = self::STATUS_NEW;
			}

			// Default Order - zero
			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			// Default Visibility - Private
			$this->visibility = $this->visibility ?? self::VISIBILITY_PRIVATE;

			// Default Cart
			$this->cart = $this->cart ?? false;

			// Default Shop
			$this->shop = $this->shop ?? false;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	public function validateInventory( $attribute, $param ) {

		if( !$this->hasErrors() ) {

			if( $this->inventory ) {

				if( empty( $this->stock ) ) {

					$this->addError( 'stock', 'Stock Quantity cannot be blank for inventory.' );
				}

				if( empty( $this->warn ) ) {

					$this->addError( 'warn', 'Warn Quantity cannot be blank for inventory.' );
				}
			}
		}
	}

	// Product -------------------------------

	/**
	 * Returns corresponding user associated with the product.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Returns primary unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getPrimaryUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'primaryUnitId' ] )->from( "$uomTable as primaryUnit" );
	}

	/**
	 * Returns purchasing unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getPurchasingUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'purchasingUnitId' ] )->from( "$uomTable as purchasingUnit" );
	}

	/**
	 * Returns quantity unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getQuantityUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'quantityUnitId' ] )->from( "$uomTable as quantityUnit" );
	}

	/**
	 * Returns weight unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getWeightUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'weightUnitId' ] )->from( "$uomTable as weightUnit" );
	}

	/**
	 * Returns volume unit associated with the item.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getVolumeUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'lengthUnitId' ] )->from( "$uomTable as volumeUnit" );
	}

	/**
	 * Returns length unit associated with the item. It will be used for length, width, height and radius.
	 *
	 * @return \cmsgears\cart\common\models\resources\Uom
	 */
	public function getLengthUnit() {

		$uomTable = Uom::tableName();

		return $this->hasOne( Uom::class, [ 'id' => 'lengthUnitId' ] )->from( "$uomTable as lengthUnit" );
	}

	/**
	 * Returns the variations available for the product.
	 *
	 * @return Variation[]
	 */
	public function getVariations() {

		return $this->hasMany( ProductVariation::className(), [ 'id' => 'productId' ] );
	}

	/**
	 * Returns string representation of type.
	 *
	 * @return string
	 */
	public function getTypeStr() {

		return static::$typeMap[ $this->type ];
	}

	public function getInventoryStr() {

		return Yii::$app->formatter->asBoolean( $this->inventory );
	}

	public function getCartStr() {

		return Yii::$app->formatter->asBoolean( $this->cart );
	}

	public function getShopStr() {

		return Yii::$app->formatter->asBoolean( $this->shop );
	}

	public function getReviewsStr() {

		return Yii::$app->formatter->asBoolean( $this->reviews );
	}

	/**
	 * Returns the total price of the item.
	 *
	 * Total Price = ( Unit Price - Unit Discount ) * Purchasing Quantity
	 *
	 * @param type $precision
	 * @return type
	 */
	public function getTotalPrice( $precision = 2 ) {

		$total = ( $this->price - $this->discount ) * $this->purchase;

		return round( $total, $precision );
	}

	public function isValidDateRange() {

		$now = DateUtil::getDateTime();

		$valid = true;

		if( !empty( $this->startDate ) ) {

			$valid = DateUtil::lessThan( $now, $this->startDate );
		}

		if( $valid && !empty( $this->endDate ) ) {

			$valid = DateUtil::lessThan( $this->endDate, $now );
		}

		return $valid;
	}

	public function inStock() {

		if( $this->inventory && $this->stock <= 0 ) {

			return false;
		}

		return true;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return ShopTables::getTableName( ShopTables::TABLE_PRODUCT );
	}

	// CMG parent classes --------------------

	// Product -------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [
			'site', 'user', 'avatar', 'purchasingUnit', 'quantityUnit',
			'weightUnit', 'volumeUnit', 'lengthUnit',
			'modelContent', 'modelContent.template'
		];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the model with avatar and content.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with avatar and content.
	 */
	public static function queryWithContent( $config = [] ) {

		$config[ 'relations' ] = [ 'avatar', 'modelContent', 'modelContent.template' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the model with avatar, content, template, banner, video and gallery.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with avatar, content, template, banner, video and gallery.
	 */
	public static function queryWithFullContent( $config = [] ) {

		$config[ 'relations' ] = [ 'avatar', 'modelContent', 'modelContent.template', 'modelContent.banner', 'modelContent.video', 'modelContent.gallery' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the content with author.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with author.
	 */
	public static function queryWithAuthor( $config = [] ) {

		$config[ 'relations' ][] = [ 'user', 'modelContent', 'modelContent.template' ];

		$config[ 'relations' ][] = [ 'user.avatar'  => function ( $query ) {
			$fileTable	= CoreTables::getTableName( CoreTables::TABLE_FILE );
			$query->from( "$fileTable avatar" ); }
		];

		return parent::queryWithAll( $config );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
