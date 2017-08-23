<?php
namespace cmsgears\shop\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\shop\common\models\base\ShopTables;
use cmsgears\shop\common\models\resources\ProductVariation;
use cmsgears\core\common\models\resources\Gallery;

use cmsgears\core\common\models\interfaces\IApproval;
use cmsgears\core\common\models\interfaces\IVisibility;

use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\SlugTypeTrait;
use cmsgears\core\common\models\traits\NameTypeTrait;
use cmsgears\core\common\models\traits\interfaces\VisibilityTrait;
use cmsgears\core\common\models\traits\interfaces\ApprovalTrait;
use cmsgears\cms\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;
use cmsgears\core\common\models\traits\mappers\TagTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Product Entity
 *
 * @property long $id
 * @property long $avatarId
 * @property long $galleryId
 * @property integer $primaryUnitId
 * @property integer $purchasingUnitId
 * @property integer $quantityUnitId
 * @property integer $weightUnitId
 * @property integer $volumeUnitId
 * @property integer $lengthUnitId
 * @property short $status
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property float $price
 * @property integer $discount
 * @property integer $sku
 * @property integer $primary
 * @property integer $purchase
 * @property integer $quantity
 * @property integer $total
 * @property integer $weight
 * @property integer $volume
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $radius
 * @property integer $visibility
 * @property string $summary
 * @property string $description
 * @property boolean $shop
 * @property long $createdBy
 * @property long $modifiedBy
 * @property datetime $createdAt
 * @property datetime $modifedAt
 * @property string $content
 * @property string $data
 */

class Product extends \cmsgears\core\common\models\base\Entity implements IApproval, IVisibility {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_REGULAR		= 0;
	const TYPE_VARIABLE		= 5;

	// Public -----------------

	public $modelType	= ShopGlobal::TYPE_PRODUCT;

	public static $typeMap = [
			self::TYPE_REGULAR => "Regular",
			self::TYPE_VARIABLE => "Variable"
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use MetaTrait;
	use CategoryTrait;
	use SlugTypeTrait;
	use NameTypeTrait;
	use VisibilityTrait;
	use ApprovalTrait;
	use ContentTrait;
	use VisualTrait;
	use AddressTrait;
	use TagTrait;

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
						'class' => AuthorBehavior::className()
				],
				'timestampBehavior' => [
						'class' => TimestampBehavior::className(),
						'createdAtAttribute' => 'createdAt',
						'updatedAtAttribute' => 'modifiedAt',
						'value' => new Expression('NOW()')
				],
				'sluggableBehavior' => [
						'class' => SluggableBehavior::className(),
						'attribute' => 'name',
						'slugAttribute' => 'slug',
						'immutable' => true,
						'ensureUnique' => true
				]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */

	public function rules() {

        return [
        		// Required, Safe
        		[ [ 'name' ], 'required' ],
        		[ [ 'id', 'avatarId', 'galleryId', 'content', 'data', 'purchase', 'status', 'slug', 'summary', 'description', 'price' ], 'safe' ],
        		// Text Limit
        		[ [ 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
        		[ [ 'name', 'sku' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
        		// Other
        		[ [ 'price', 'discount', 'purchase', 'quantity', 'total', 'weight', 'volume', 'length', 'width', 'height', 'radius', 'visibility', 'shop' ], 'number', 'min' => 0 ],
        		[ [ 'purchasingUnitId', 'quantityUnitId', 'uomId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
        		[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
        		[ [ 'startDate', 'endDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ]
        ];
    }

    /**
     * @inheritdoc
     */

	public function attributeLabels() {

		return [
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Product -------------------------------

	public function getTypeStr() {

		return self::$typeMap[ $this->type ];
	}

	public function getGallery() {

		return $this->hasOne( Gallery::className(), [ 'id' => 'galleryId' ] );
	}

	public function getVariation() {

		return $this->hasOne( ProductVariation::className(), [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */

	public static function tableName() {

		return ShopTables::TABLE_PRODUCT;
	}

	// CMG parent classes --------------------

	// Product -------------------------------

	// Read - Query -----------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>