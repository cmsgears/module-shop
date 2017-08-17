<?php
namespace cmsgears\shop\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\shop\common\models\base\ShopTables;

use cmsgears\shop\common\services\interfaces\entities\IProductService;

class ProductService extends \cmsgears\core\common\services\base\EntityService implements IProductService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\shop\common\models\entities\Product';

	public static $modelTable	= ShopTables::TABLE_PRODUCT;

	public static $parentType	= ShopGlobal::TYPE_PRODUCT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

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
					'label' => 'name'
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

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
				'attributes' => [ 'name', 'description', 'type' ]
		]);
	}

	public function linkGallery( $model, $gallery ) {

		$model->galleryId	= $gallery->id;

		return parent::update( $model, [
				'attributes' => [ 'galleryId' ]
		]);
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
