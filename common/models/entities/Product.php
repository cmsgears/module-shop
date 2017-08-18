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

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Product Entity
 *
 * @property long $id
 * @property long $avatarId
 * @property long $galleryId
 * @property short $status
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property integer $visibility
 * @property string $summary
 * @property string $description
 * @property string $content
 * @property float $price
 * @property long $createdBy
 * @property long $modifiedBy
 * @property datetime $createdAt
 * @property datetime $modifedAt
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
            [ [ 'name' ], 'required' ],
        	[ [ 'id', 'avatarId', 'status', 'slug', 'type', 'summary', 'description', 'content', 'price', 'galleryId' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'visibility' ], 'number', 'integerOnly' => true ]
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