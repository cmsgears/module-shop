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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\cms\common\models\resources\ModelContent;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\shop\common\services\interfaces\entities\IProductService;
use cmsgears\shop\common\services\interfaces\resources\IProductMetaService;

use cmsgears\core\common\services\traits\base\SimilarTrait;
use cmsgears\core\common\services\traits\mappers\CategoryTrait;

/**
 * ProductService provide service methods of product model.
 *
 * @since 1.0.0
 */
class ProductService extends \cmsgears\cms\common\services\base\ContentService implements IProductService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\shop\common\models\entities\Product';

	public static $parentType = ShopGlobal::TYPE_PRODUCT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use CategoryTrait;
	use SimilarTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, IProductMetaService $metaService, $config = [] ) {

		$this->fileService	= $fileService;
		$this->metaService 	= $metaService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductService ------------------------

	// Data Provider ------

	// Data Provider ----------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$contentTable	= Yii::$app->factory->get( 'modelContentService' )->getModelTable();
		$templateTable	= Yii::$app->factory->get( 'templateService' )->getModelTable();

		$categoryTable	= Yii::$app->factory->get( 'categoryService' )->getModelTable();
		$tagTable		= Yii::$app->factory->get( 'tagService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'template' => [
					'asc' => [ "$templateTable.name" => SORT_ASC ],
					'desc' => [ "$templateTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Template',
				],
				'name', 'slug', 'type', 'icon', 'title', 'status', 'visibility',
				'order', 'price', 'discount', 'total', 'inventory', 'stock', 'sold',
				'cart', 'shop', 'pinned', 'featured',
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
				'pdate' => [
					'asc' => [ "modelContent.publishedAt" => SORT_ASC ],
					'desc' => [ "modelContent.publishedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Published At'
				],
				// Conditional - Check for proper joins before applying the sort
				'category' => [
					'asc' => [ "$categoryTable.name" => SORT_ASC ],
					'desc' => [ "$categoryTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Category'
				],
				'tag' => [
					'asc' => [ "$tagTable.name" => SORT_ASC ],
					'desc' => [ "$tagTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Tag'
				],
				'rating' => [
					'asc' => [ "rating" => SORT_ASC ],
					'desc' => [ "rating" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Rating'
				]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		// Filters ----------

		// Params
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'cart': {

					$config[ 'conditions' ][ "$modelTable.cart" ] = true;

					break;
				}
				case 'shop': {

					$config[ 'conditions' ][ "$modelTable.shop" ] = true;

					break;
				}
			}
		}

		// Searching --------

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'summary' => "modelContent.summary",
			'content' => "modelContent.content",
			'status' => "$modelTable.status",
			'visibility' => "$modelTable.visibility",
			'order' => "$modelTable.order",
			'price' => "$modelTable.price",
			'total' => "$modelTable.total",
			'cart' => "$modelTable.cart",
			'shop' => "$modelTable.shop",
			'inventory' => "$modelTable.inventory",
			'stock' => "$modelTable.stock",
			'sold' => "$modelTable.sold",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured",
			'popular' => "$modelTable.popular"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPublicPage( $config = [] ) {

		$config[ 'route' ] = isset( $config[ 'route' ] ) ? $config[ 'route' ] : 'product';

		return parent::getPublicPage( $config );
	}

	public function getPageForSimilar( $config = [] ) {

		$modelClass	= static::$modelClass;

		$config[ 'query' ] = isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::queryWithContent();
		$config[ 'query' ] = $this->generateSimilarQuery( $config );

		return $this->getPublicPage( $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model ) {

		return isset( $model->userId ) ? $model->user->email : $model->creator->email;
	}

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Refresh Total
		$model->total = $model->getTotalPrice();

		return parent::create( $model, $config );
	}

	public function add( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelClass = static::$modelClass;
		$parentType	= static::$parentType;

		$content		= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish		= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner			= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner		= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video			= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo			= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery		= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$mappingsType	= isset( $config[ 'mappingsType' ] ) ? $config[ 'mappingsType' ] : static::$parentType;

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		$galleryClass = $galleryService->getModelClass();

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $content->template;

			$this->copyTemplate( $model, $config );

			// Create Model
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->siteId	= $model->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => $model->siteId,
					'type' => $parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => $parentType,
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);

			// Bind categories
			$modelCategoryService->bindModels( $model->id, $mappingsType, [ 'binder' => 'CategoryBinder' ] );

			// Bind tags
			$modelTagService->bindTags( $model->id, $mappingsType, [ 'binder' => 'TagBinder' ] );

			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		return $model;
	}

	public function register( $model, $config = [] ) {

		$notify	= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$mail	= isset( $config[ 'mail' ] ) ? $config[ 'mail' ] : true;
		$user	= isset( $config[ 'user' ] ) ? $config[ 'user' ] : Yii::$app->core->getUser();

		$modelClass = static::$modelClass;
		$parentType	= static::$parentType;

		$content		= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish		= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner			= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner		= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video			= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo			= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery		= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$mappingsType	= isset( $config[ 'mappingsType' ] ) ? $config[ 'mappingsType' ] : static::$parentType;
		$adminLink		= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : 'shop/product/review';

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		$galleryClass = $galleryService->getModelClass();

		$registered	= false;

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $content->template;

			$this->copyTemplate( $model, $config );

			// Create Model
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->siteId	= $model->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => $model->siteId,
					'type' => $parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => $parentType,
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);

			// Bind categories
			$modelCategoryService->bindModels( $model->id, $mappingsType, [ 'binder' => 'CategoryBinder' ] );

			// Bind tags
			$modelTagService->bindTags( $model->id, $mappingsType, [ 'binder' => 'TagBinder' ] );

			$transaction->commit();

			$registered	= true;
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		if( $registered ) {

			// Notify Site Admin
			if( $notify ) {

				$this->notifyAdmin( $model, [
					'template' => ShopGlobal::TPL_NOTIFY_PRODUCT_NEW,
					'adminLink' => "{$adminLink}?id={$model->id}"
				]);
			}

			// Email Product Admin
			if( $mail ) {

				Yii::$app->shopMailer->sendRegisterProductMail( $model );
			}
		}

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$content		= isset( $config[ 'content' ] ) ? $config[ 'content' ] : null;
		$publish		= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$avatar			= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner			= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner		= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video			= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo			= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery		= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$mappingsType	= isset( $config[ 'mappingsType' ] ) ? $config[ 'mappingsType' ] : static::$parentType;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId',
			'avatarId', 'name', 'slug', 'icon', 'texture', 'title', 'description', 'visibility', 'content',
			'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius',
			'sku', 'code', 'cart', 'shop', 'shopNotes', 'price', 'discount', 'total', 'startDate', 'endDate',
			'inventory', 'stock', 'sold', 'warn'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured', 'popular', 'reviews'
			]);
		}

		// Copy Template
		if( isset( $content ) ) {

			$config[ 'template' ] = $content->template;

			if( $this->copyTemplate( $model, $config ) ) {

				$attributes[] = 'data';
			}
		}

		// Services
		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Create/Update gallery
		if( isset( $gallery ) ) {

			$gallery = $galleryService->createOrUpdate( $gallery );
		}

		// Update model content
		if( isset( $content ) ) {

			$modelContentService->update( $content, [
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);
		}

		// Refresh Total
		$model->total = $model->getTotalPrice();

		// Bind categories
		$modelCategoryService->bindModels( $model->id, $mappingsType, [ 'binder' => 'CategoryBinder' ] );

		// Bind tags
		$modelTagService->bindTags( $model->id, $mappingsType, [ 'binder' => 'TagBinder' ] );

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Product status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$config[ 'hard' ] = $config[ 'hard' ] ?? !Yii::$app->core->isSoftDelete();

		if( $config[ 'hard' ] ) {

			$transaction = Yii::$app->db->beginTransaction();

			try {

				// Delete metas
				$this->metaService->deleteByModelId( $model->id );

				// Delete files
				$this->fileService->deleteMultiple( ArrayHelper::merge( $model->files, [ $model->avatar ] ) );

				// Delete Model Content, Gallery, Banner, Video
				Yii::$app->factory->get( 'modelContentService' )->delete( $model->modelContent );

				// Delete Category Mappings
				Yii::$app->factory->get( 'modelCategoryService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Tag Mappings
				Yii::$app->factory->get( 'modelTagService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Option Mappings
				Yii::$app->factory->get( 'modelOptionService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Comments
				Yii::$app->factory->get( 'modelCommentService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Followers
				Yii::$app->factory->get( 'productFollowerService' )->deleteByModelId( $model->id );

				$transaction->commit();
			}
			catch( Exception $e ) {

				$transaction->rollBack();

				throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY ) );
			}
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		$config[ 'users' ] = isset( $config[ 'users' ] ) ? $config[ 'users' ] : ( isset( $model->userId ) ? [ $model->userId ] : [] );

		return parent::applyBulk( $model, $column, $action, $target, $config );
	}

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
