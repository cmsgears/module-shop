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

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\shop\common\services\interfaces\entities\IProductService;
use cmsgears\shop\common\services\interfaces\resources\IProductMetaService;

use cmsgears\cms\common\services\base\ContentService;

use cmsgears\core\common\services\traits\base\FeaturedTrait;
use cmsgears\core\common\services\traits\base\SimilarTrait;
use cmsgears\core\common\services\traits\resources\VisualTrait;
use cmsgears\core\common\services\traits\mappers\CategoryTrait;

/**
 * ProductService provide service methods of product model.
 *
 * @since 1.0.0
 */
class ProductService extends ContentService implements IProductService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\shop\common\models\entities\Product';

	public static $parentType	= ShopGlobal::TYPE_PRODUCT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use CategoryTrait;
	use FeaturedTrait;
	use SimilarTrait;
	use VisualTrait;

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
				'order', 'price', 'discount', 'total', 'track', 'stock', 'sold', 'shop', 'pinned', 'featured',
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
					'asc' => [ "$contentTable.publishedAt" => SORT_ASC ],
					'desc' => [ "$contentTable.publishedAt" => SORT_DESC ],
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
			'defaultOrder' => [
				'id' => SORT_DESC
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		// Filters ----------

		$softDelete = $modelClass::STATUS_DELETED;

		$config[ 'conditions' ][] = "$modelTable.status!=$softDelete";

		// Params
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'shop': {

					$config[ 'conditions' ][ "$modelTable.shop" ] = true;

					break;
				}
				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name", 'title' => "$modelTable.title", 'desc' => "$modelTable.description",
				'summary' => "modelContent.summary", 'content' => "modelContent.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'title' => "$modelTable.title", 'desc' => "$modelTable.description",
			'summary' => "modelContent.summary", 'content' => "modelContent.content",
			'status' => "$modelTable.status", 'visibility' => "$modelTable.visibility", 'order' => "$modelTable.order",
			'price' => "$modelTable.price", 'total' => "$modelTable.total",
			'shop' => "$modelTable.shop", 'track' => "$modelTable.track", 'stock' => "$modelTable.stock", 'sold' => "$modelTable.sold",
			'pinned' => "$modelTable.pinned", 'featured' => "$modelTable.featured"
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

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass = static::$modelClass;

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Default Private
		if( !isset( $model->visibility ) ) {

			$model->visibility = $modelClass::VISIBILITY_PRIVATE;
		}

		// Default New
		if( !isset( $model->status ) ) {

			$model->status = $modelClass::STATUS_NEW;
		}

		$model->total = $model->getTotalPrice();

		return parent::create( $model, $config );
	}

	public function add( $model, $config = [] ) {

		return $this->register( $model, $config );
	}

	public function register( $model, $config = [] ) {

		$content 	= $config[ 'content' ];
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		$galleryClass = $galleryService->getModelClass();

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Create Product
			$model = $this->create( $model, $config );

			// Create gallery
			if( $gallery ) {

				$gallery->siteId	= empty( $gallery->siteId ) ? $model->siteId : $gallery->siteId;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;
				$gallery->type		= ShopGlobal::TYPE_PRODUCT;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;

				$gallery = $galleryService->create( $gallery );
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => static::$parentType,
				'publish' => true,
				'banner' => $banner, 'video' => $video, 'gallery' => $gallery
			]);

			// Bind categories
			$modelCategoryService->bindCategories( $model->id, ShopGlobal::TYPE_PRODUCT, [ 'binder' => 'CategoryBinder' ] );

			// Bind tags
			$modelTagService->bindTags( $model->id, ShopGlobal::TYPE_PRODUCT, [ 'binder' => 'TagBinder' ] );

			$transaction->commit();

			return $model;
		}
		catch( Exception $e ) {

			$transaction->rollBack();
		}

		return false;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$content 	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : $model->modelContent;
		$admin 		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$avatar 	= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'primaryUnitId', 'purchasingUnitId', 'quantityUnitId', 'weightUnitId', 'volumeUnitId', 'lengthUnitId',
			'avatarId', 'name', 'slug', 'icon',
			'title', 'description', 'visibility', 'content',
			'primary', 'purchase', 'quantity', 'weight', 'volume', 'length', 'width', 'height', 'radius',
			'sku', 'code', 'shop', 'shopNotes', 'price', 'discount', 'total', 'startDate', 'endDate',
			'track', 'stock', 'sold', 'warn'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status', 'order', 'pinned', 'featured', 'reviews' ] );
		}

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Create/Update gallery
		if( isset( $gallery ) ) {

			$gallery = $galleryService->createOrUpdate( $gallery );
		}

		// Update model content
		$modelContentService->update( $content, [
			'publish' => true, 'banner' => $banner, 'video' => $video, 'gallery' => $gallery
		]);

		// Update total price
		$model->total = $model->getTotalPrice();

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Delete metas
			$this->metaService->deleteByModelId( $model->id );

			// Delete files
			$this->fileService->deleteFiles( [ $model->avatar ] );
			$this->fileService->deleteFiles( $model->files );

			// Delete Model Content
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

			// Delete model
			return parent::delete( $model, $config );
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
		}

		return false;
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
