<?php
namespace cmsgears\shop\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\cms\common\models\resources\ModelContent;

class ProductController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $modelContentService;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission	= ShopGlobal::PERM_SHOP;

		// Services
		$this->modelService			= Yii::$app->factory->get( 'productService' );
		$this->modelContentService	= Yii::$app->factory->get( 'modelContentService' );

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
}
