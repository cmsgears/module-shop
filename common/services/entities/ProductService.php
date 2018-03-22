<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\shop\common\models\base\ShopTables;

use cmsgears\core\common\models\resources\Gallery;
use cmsgears\shop\common\models\entities\Product;

use cmsgears\core\common\services\traits\SlugTypeTrait;
use cmsgears\core\common\services\traits\ApprovalTrait;

use cmsgears\shop\common\services\interfaces\entities\IProductService;

use cmsgears\core\common\services\base\EntityService;

/**
 * ProductService provide service methods of product model.
 *
 * @since 1.0.0
 */
class ProductService extends EntityService implements IProductService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	public $fileService;

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\shop\common\models\entities\Product';

	public static $modelTable	= ShopTables::TABLE_PRODUCT;

	public static $typed		= true;

	public static $parentType	= ShopGlobal::TYPE_PRODUCT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use SlugTypeTrait;
    use ApprovalTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->fileService	= Yii::$app->factory->get( 'fileService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductService ------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'price' => [
					'asc' => [ 'price' => SORT_ASC ],
					'desc' => ['price' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Price'
				],
				'quantity' => [
					'asc' => [ 'quantity' => SORT_ASC ],
					'desc' => ['quantity' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Quantity'
				],
				'cdate' => [
					'asc' => [ 'createdAt' => SORT_ASC ],
					'desc' => ['createdAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'cdate',
				],
				'udate' => [
					'asc' => [ 'modifiedAt' => SORT_ASC ],
					'desc' => ['modifiedAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'udate',
				]
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'template' => "$modelTable.template" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
				'name' => "$modelTable.name", 'slug' => "$modelTable.slug"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$model	= parent::create( $model, $config );

		$this->linkGallery( $model );

		return $model;
	}

	// Update -------------

    public function submit( $model ) {

        $this->notifyAdmin( $model );
    }

	public function update( $model, $config = [] ) {

		$attributes	= [ 'name', 'description', 'type', 'status', 'visibility', 'shop', 'quantity', 'price', 'startDate', 'endDate', 'content', 'uomId' ];

		return parent::update( $model, [
				'attributes' => $attributes
		]);
	}

	public function linkGallery( $product ) {

		$model 			= new Gallery();
		$model->name	= $product->name;
		$model->type	= ShopGlobal::TYPE_PRODUCT;
		$model->siteId	= Yii::$app->core->siteId;

		$gallery		= Yii::$app->factory->get( 'galleryService' )->create( $model );

		if( isset( $gallery ) ) {

			$product->galleryId	= $gallery->id;

			return parent::update( $product, [
					'attributes' => [ 'galleryId' ]
			]);
		}
	}

	public function updateAvatar( $product, $avatar ) {

		// Save Avatar
		$this->fileService->saveFiles( $product, [ 'avatarId' => $avatar ] );

		$product->avatarId	= $avatar->id;

		return parent::update( $product, [ 'attributes' => [ 'avatarId' ] ] );
	}

    // Trigger Admin Notifications where applicable and Update status
	protected function notifyAdmin( $model, $config = [] ) {

		$config[ 'admin' ]	= true;

		if( $model->status < Product::STATUS_SUBMITTED ) {

			$this->updateStatus( $model, Product::STATUS_SUBMITTED );

			$config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_SUBMIT;
			$config[ 'title' ]      = ShopGlobal::TITLE_REGISTERED;

			// Send admin notification for new product.
			$this->sendNotification( $model, $config );

			$model->refresh();
		}
        else if( $model->isRejected() ) {

			$this->updateStatus( $model, Product::STATUS_RE_SUBMIT );

			$config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_RESUBMIT;
			$config[ 'title' ]      = ShopGlobal::TITLE_RESUBMIT;

			// Send admin notification for re-submit product.
			$this->sendNotification( $model, $config );
		}
        else if( $model->isFrojen() || $model->isBlocked() ) {

			if( $model->isFrojen() ) {

				$this->updateStatus( $model, Product::STATUS_UPLIFT_FREEZE );

				$config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_UP_FREEZE;
				$config[ 'title' ]      = ShopGlobal::TITLE_UPLIFT_FREEZE;

				// Send admin notification for uplift freeze.
				$this->sendNotification( $model, $config );
			}

			if( $model->isBlocked() ) {

				$this->updateStatus( $model, Product::STATUS_UPLIFT_BLOCK );

				$config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_UP_BLOCK;
				$config[ 'title' ]      = ShopGlobal::TITLE_UPLIFT_BLOCK;

				// Send admin notification for uplift block.
				$this->sendNotification( $model, $config );
			}
		}
	}

    // Trigger User Notifications where applicable and Update status
    public function notifyUser( $model, $config = [] ) {

        $email	= $model->creator->email;
        $status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : $model->status;

        $config[ 'admin' ]	= false;

        switch( $status ) {

            case Product::STATUS_ACTIVE: {

                $this->approve( $model );

                $config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_APPROVE;
                $config[ 'title' ]		= ShopGlobal::TITLE_APPROVE;

                $this->sendNotification( $model, $config );

                break;
            }

            case Product::STATUS_REJECTED: {

                $message    = $this->getMessage();

                $this->reject( $model, $message );

                $config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_REJECT;
                $config[ 'title' ]      = ShopGlobal::TITLE_REJECT;
                $config[ 'message' ]	= $message;

                $this->sendNotification( $model, $config );

                break;
            }

            case Product::STATUS_FROJEN: {

                $message    = $this->getMessage();

                $this->freeze( $model, $message );

                $config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_FREEZE;
                $config[ 'title' ]      = ShopGlobal::TITLE_FREEZE;
                $config[ 'message' ]	= $message;

                $this->sendNotification( $model, $config );

                break;
            }

            case Product::STATUS_BLOCKED: {

                $message    = $this->getMessage();

                $this->block( $model, $message );

                $config[ 'template' ]	= ShopGlobal::TEMPLATE_NOTIFY_BLOCKED;
                $config[ 'title' ]      = ShopGlobal::TITLE_BLOCKED;
                $config[ 'message' ]	= $message;

                $this->sendNotification( $model, $config );

                break;
            }
        }
	}

    protected function sendNotification( $product, $config = [] ) {

		$templateType           = $config[ 'template' ];
		$title                  = $config[ 'title' ];
		$id                     = $product->id;
		$name                   = $product->name;
		$templateVars           = [];
		$templateConfig         = [];

		$templateConfig[ 'parentId' ]	= $id;
		$templateConfig[ 'parentType' ]	= self::$parentType;
		$templateConfig[ 'title' ]      = $title;

		if( isset( $config[ 'admin' ] ) && $config[ 'admin' ] ) {

			$templateConfig[ 'adminLink' ]	= "/shop/product/watch?id=$id";
		}
		else {

            $templateConfig[ 'link' ]	= "/shop/product/review?id=$id";
			$templateConfig[ 'users' ]	= [ $product->createdBy ];
		}

		$templateVars[ 'productName' ]	= $name;

		if( isset( $config[ 'message' ] ) && $config[ 'message' ] != null ) {

			$templateVars[ 'message' ]	= $config[ 'message' ];
		}

		return Yii::$app->eventManager->triggerNotification( $templateType, $templateVars, $templateConfig );
	}

    protected function getMessage() {

        return Yii::$app->request->post( 'message' ) != null ? Yii::$app->request->post( 'message' ) : "No reason were specified.";
	}

	// Delete -------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ProductService ------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
