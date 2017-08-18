<?php
namespace cmsgears\shop\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\cms\common\models\resources\ModelContent;
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\utilities\CodeGenUtil;

// SF Imports
use safaricities\core\common\config\CoreGlobal;

class ProductController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $provinceService;
	public $lgaService;
	public $modelContentService;
	public $modelAddressService;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission	= ShopGlobal::PERM_SHOP;

		// Services
		$this->modelService			= Yii::$app->factory->get( 'productService' );
		$this->provinceService		= Yii::$app->factory->get( 'provinceService' );
		$this->lgaService			= Yii::$app->factory->get( 'lgaService' );
		$this->modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$this->modelAddressService	= Yii::$app->factory->get( 'modelAddressService' );

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-shop', 'child' => 'product' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'products' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs		= [
			'all' => [ [ 'label' => 'Products' ] ],
			'create' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductController ---------------------

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'Products' );

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate() {

		$modelClass			= $this->modelService->getModelClass();
		$model				= new $modelClass;
		$content			= new ModelContent();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&

			$model->validate() && $content->validate() ) {

			$this->modelService->create( $model, [ 'admin' => true ] );

			$this->modelContentService->create( $content, [ 'parent' => $model, 'parentType' => ShopGlobal::TYPE_PRODUCT ] );

			return $this->redirect( "update?id=$model->id" );
		}

		return $this->render( 'create', [
				'model' => $model,
				'content' => $content,
				'typeMap' => $modelClass::$typeMap
		]);
	}

	public function actionUpdate( $id ) {

		$model	= $this->modelService->getById( $id );

		if( isset( $model ) ) {

			$content			= $model->modelContent;
			$modelClass			= $this->modelService->getModelClass();

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&

				$model->validate() && $content->validate() ) {

				$this->modelService->update( $model, [ 'admin' => true ] );

				$this->modelContentService->update( $content );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
					'model' => $model,
					'content' => $content,
					'typeMap' => $modelClass::$typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionLocation( $id ) {

		$product	= $this->modelService->getById( $id );

		if( isset( $product ) ) {

			$model	= isset( $product->primaryAddress ) ? $product->primaryAddress : new Address();

			$model->countryId	= CoreGlobal::COUNTRY_NIGERIA;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelAddressService->createOrUpdate( $model, [ 'parentId' => $product->id, 'parentType' => ShopGlobal::TYPE_PRODUCT, 'type' => Address::TYPE_PRIMARY ] );

				return $this->refresh();
			}

			$provinceId		= CoreGlobal::PROVINCE_ABIA;
			$provinceMap	= $this->provinceService->getMapByCountryId( CoreGlobal::COUNTRY_NIGERIA );
			$lgaList		= $this->lgaService->getByProvinceId( $provinceId );
			$lgaMap			= CodeGenUtil::generateIdNameMap( $lgaList );
			$lgaMap			= array_filter( $lgaMap ); // Remove empty array elements

			return $this->render( 'location', [
					'model' => $model,
					'product' => $product,
					'provinceMap' => $provinceMap,
					'typeMap' => $typeMap,
					'lgaMap' => $lgaMap,
					'provinceId' => $provinceId
			] );
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
