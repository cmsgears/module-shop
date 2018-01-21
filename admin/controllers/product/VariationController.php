<?php
namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\shop\common\models\base\ShopTables;

class VariationController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $productService;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-shop/admin/views/product/variation' );

		// Services
		$this->modelService		= Yii::$app->factory->get( 'productVariationService' );
		$this->productService	= Yii::$app->factory->get( 'productService' );

		// Sidebar
		$this->sidebar		= [ 'parent' => 'sidebar-shop', 'child' => 'shop' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'products' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/shop/all/' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/shop/all' ] ] ],
			'all' => [ [ 'label' => 'Variations' ] ],
			'create' => [ [ 'label' => 'Variations', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Variations', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// VariationController -------------------

	public function actionAll( $pid = null ) {

		if( isset( $pid ) ) {

			$variationTable	= ShopTables::TABLE_PRODUCT_VARIATION;

			Url::remember( Yii::$app->request->getUrl(), 'Variations' );

			$product		= $this->productService->getById( $pid );

			$dataProvider	= $this->modelService->getPage( [ 'conditions' => [ "$variationTable.modelId=$pid" ] ] );

			if( isset( $product) ) {

				return $this->render( 'all', [
					'dataProvider' => $dataProvider,
					'product' => $product
				] );
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $id = null ) {

		$product	= $this->productService->getById( $id );

		if( isset( $product ) ) {

			$modelClass	= $this->modelService->getModelClass();
			$model		= new $modelClass;
			$productId	= $product->id;

			$model->modelId	= $productId;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

				$this->modelService->create( $model );

				return $this->redirect( "update?id=$model->id&pid=$productId" );
			}

			$typeMap	= $modelClass::$typeMap;

			return $this->render( 'create', [
					'model' => $model,
					'product' => $product,
					'typeMap' => $typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $pid = null ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Find Product
		$product	= $this->productService->getById( $pid );

		// Update if exist
		if( isset( $model ) && isset( $product ) ) {

			$modelClass	= $this->modelService->getModelClass();
			$typeMap	= $modelClass::$typeMap;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->refresh();
			}

			// Render view
			return $this->render( 'update', [
					'model' => $model,
					'product' => $product,
					'typeMap' => $typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}