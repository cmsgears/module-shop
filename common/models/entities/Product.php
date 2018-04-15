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
use cmsgears\core\common\models\interfaces\base\IFollower;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\base\IVisibility;
use cmsgears\core\common\models\interfaces\resources\IComment;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\ICategory;
use cmsgears\core\common\models\interfaces\mappers\IFile;
use cmsgears\core\common\models\interfaces\mappers\ITag;
use cmsgears\cms\common\models\interfaces\resources\IPageContent;

use cmsgears\core\common\models\base\Entity;
use cmsgears\core\common\models\resources\File;
use cmsgears\cart\common\models\resources\Uom;
use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\resources\Variation;
use cmsgears\shop\common\models\mappers\ProductFollower;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\FollowerTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\CommentTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\mappers\TagTrait;
use cmsgears\cms\common\models\traits\resources\PageContentTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Product model represents product to be sold by shop.
 *
 * @property integer $id
 * @property integer $siteId
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
 * @property boolean $track
 * @property float $stock
 * @property float $sold
 * @property float $warn
 * @property boolean $shop
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $reviews
 * @property datetime $startDate
 * @property datetime $endDate
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
class Product extends Entity implements IApproval, IAuthor, ICategory, IComment, IContent, IData, IFeatured, IFile, IFollower,
	IGridCache, IMultiSite, INameType, IPageContent, ISlugType, ITag, IVisibility, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = ShopGlobal::TYPE_PRODUCT;

	protected $followerTable;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ApprovalTrait;
    use AuthorTrait;
	use CategoryTrait;
	use CommentTrait;
	use ContentTrait;
    use DataTrait;
	use FeaturedTrait;
	use FileTrait;
	use FollowerTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use PageContentTrait;
	use SlugTypeTrait;
	use TagTrait;
	use VisibilityTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->followerTable = ProductFollower::tableName();
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
			[ [ 'purchasingUnitId', 'name', 'price', 'purchase' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Unique
			[ [ 'siteId', 'slug' ], 'unique', 'targetAttribute' => [ 'siteId', 'slug' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'slug', 'sku', 'code' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'status', 'visibility', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'price', 'discount', 'primary', 'purchase', 'quantity', 'total', 'weight', 'volume', 'length', 'width', 'height', 'radius', 'track', 'stock', 'sold', 'warn' ], 'number', 'min' => 0 ],
			[ [ 'shop', 'pinned', 'featured', 'reviews', 'gridCacheValid' ], 'boolean' ],
			[ [ 'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'avatarId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate' ], 'date' ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End Date must be greater than or equal to Start Date.' ]
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
            'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
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
			'track' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_TRACK ),
			'stock' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_STOCK ),
			'sold' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_SOLD ),
			'warn' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_QUANTITY_WARN ),
			'shop' => Yii::$app->shopMessage->getMessage( ShopGlobal::FIELD_SHOP ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
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

			if( $this->primaryUnitId <= 0 ) {

				$this->primaryUnitId = null;
			}

			if( $this->quantityUnitId <= 0 ) {

				$this->quantityUnitId = null;
			}

			if( $this->weightUnitId <= 0 ) {

				$this->weightUnitId = null;
			}

			if( $this->volumeUnitId <= 0 ) {

				$this->volumeUnitId = null;
			}

			if( $this->lengthUnitId <= 0 ) {

				$this->lengthUnitId = null;
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

	// Product -------------------------------

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
	 * Returns page or post followers.
	 *
	 * @return \cmsgears\shop\common\models\mappers\ProductFollower[]
	 */
	public function getProductFollowers() {

		return $this->hasMany( ProductFollower::class, [ 'modelId' => 'id' ] );
	}

	/**
	 * Returns meta and attributes.
	 *
	 * @return \cmsgears\shop\common\models\resources\ProductMeta[]
	 */
	public function getMetas() {

		return $this->hasMany( ProductMeta::class, [ 'productId' => 'id' ] );
	}

	/**
	 * Returns the variations available for the product.
	 *
	 * @return Variation[]
	 */
	public function getVariations() {

		return $this->hasMany( Variation::className(), [ 'id' => 'productId' ] );
	}

	/**
	 * Returns string representation of type.
	 *
	 * @return string
	 */
	public function getTypeStr() {

		return static::$typeMap[ $this->type ];
	}

	public function getTrackStr() {

		return Yii::$app->formatter->asBoolean( $this->track );
	}

	public function getShopStr() {

		return Yii::$app->formatter->asBoolean( $this->shop );
	}

	public function getReviewsStr() {

		return Yii::$app->formatter->asBoolean( $this->reviews );
	}

	/**
	 * Check whether product is published.
	 *
	 * @return boolean
	 */
	public function isPublished() {

		return $this->visibility == self::VISIBILITY_PUBLIC && ( $this->status == self::STATUS_ACTIVE || $this->status == self::STATUS_FROJEN );
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
			'purchasingUnit', 'quantityUnit', 'weightUnit', 'volumeUnit', 'lengthUnit',
			'avatar', 'modelContent', 'modelContent.template', 'site', 'creator', 'modifier' ];

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
	 * Return query to find the model with avatar, content, template, banner, author and author avatar.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with avatar, content, template, banner, author and author avatar.
	 */
	public static function queryWithAuthor( $config = [] ) {

		$config[ 'relations' ][] = [ 'avatar', 'modelContent', 'modelContent.template', 'modelContent.banner', 'creator' ];

		$config[ 'relations' ][] = [ 'creator.avatar'  => function ( $query ) {
			$fileTable = File::tableName();
			$query->from( "$fileTable aavatar" ); }
		];

		return parent::queryWithAll( $config );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
