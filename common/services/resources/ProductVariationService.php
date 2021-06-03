<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\shop\common\services\interfaces\resources\IProductVariationService;

use cmsgears\core\common\services\traits\resources\VisualTrait;

/**
 * ProductVariationService provide service methods of product variation.
 *
 * @since 1.0.0
 */
class ProductVariationService extends \cmsgears\core\common\services\base\ResourceService implements IProductVariationService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\shop\common\models\resources\ProductVariation';

	public static $parentType = ShopGlobal::TYPE_PRODUCT_VARIATION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService = $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductVariationService ---------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$templateTable = Yii::$app->factory->get( 'templateService' )->getModelTable();

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
					'default' => SORT_ASC,
					'label' => 'Template',
				],
				'product' => [
					'asc' => [ "product.name" => SORT_ASC ],
					'desc' => [ "product.name" => SORT_DESC ],
					'default' => SORT_ASC,
					'label' => 'Product',
				],
				'addon' => [
					'asc' => [ "addon.name" => SORT_ASC ],
					'desc' => [ "addon.name" => SORT_DESC ],
					'default' => SORT_ASC,
					'label' => 'Addon',
				],
				'unit' => [
					'asc' => [ "uom.name" => SORT_ASC ],
					'desc' => [ "uom.name" => SORT_DESC ],
					'default' => SORT_ASC,
					'label' => 'Name'
				],
				'name', 'type', 'icon', 'title', 'order',
				'dtype' => [
					'asc' => [ "$modelTable.discountType" => SORT_ASC ],
					'desc' => [ "$modelTable.discountType" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Discount Type'
				],
				'price', 'discount', 'total', 'quantity', 'inventory', 'stock', 'sold', 'active',
				'sdate' => [
					'asc' => [ "$modelTable.startDate" => SORT_ASC ],
					'desc' => [ "$modelTable.startDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Start Date'
				],
				'edate' => [
					'asc' => [ "$modelTable.endDate" => SORT_ASC ],
					'desc' => [ "$modelTable.endDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'End Date'
				],
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

		// Params
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) && isset( $modelClass::$urlRevTypeMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $modelClass::$urlRevTypeMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

					break;
				}
				case 'inactive': {

					$config[ 'conditions' ][ "$modelTable.active" ] = false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'active' => "$modelTable.active",
			'order' => "$modelTable.order",
			'dtype' => "$modelTable.discountType",
			'price' => "$modelTable.price",
			'discount' => "$modelTable.discount",
			'total' => "$modelTable.total",
			'inventory' => "$modelTable.inventory",
			'stock' => "$modelTable.stock",
			'sold' => "$modelTable.sold"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByProductId( $productId, $config = [] ) {

		$config[ 'conditions' ][ 'productId' ] = $productId;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass = static::$modelClass;

		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'bannerId' => $banner, 'videoId' => $video ] );

		// Refresh Price
		$model->total = $model->getTotalPrice();

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'templateId', 'productId', 'addonId', 'unitId', 'bannerId', 'videoId',
			'name', 'type', 'icon', 'title', 'description', 'content',
			'order', 'discountType', 'price', 'discount', 'total', 'quantity', 'free',
			'inventory', 'stock', 'sold', 'warn', 'active', 'startDate', 'endDate'
		];

		// Save Files
		$this->fileService->saveFiles( $model, [ 'bannerId' => $banner, 'videoId' => $video ] );

		// Refresh Price
		$model->total = $model->getTotalPrice();

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete files
		$this->fileService->deleteFiles( [ $model->banner, $model->video ] );

		// Delete Gallery
		Yii::$app->factory->get( 'galleryService' )->delete( $model->gallery );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'inactive': {

						$model->active = false;

						$model->update();

						break;
					}
					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ProductVariationService ---------------

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
