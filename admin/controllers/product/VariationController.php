<?php
namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
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
		$this->sidebar		= [ 'parent' => 'sidebar-shop', 'child' => 'product' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'products' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/all/' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/product/all' ] ] ],
			'all' => [ [ 'label' => 'Variations' ] ],
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

	public function actionAll( $pid = null ) {

		if( isset( $pid ) ) {

			$variationTable	= ShopTables::TABLE_PRODUCT_VARIATION;

			Url::remember( Yii::$app->request->getUrl(), 'Variations' );

			$dataProvider = $this->modelService->getPage( [ 'conditions' => [ "$variationTable.modelId=$pid" ] ] );

			if( isset( $models ) ) {

				return $this->render( 'all', [
					'models' => $models,
					'product' => $product
				] );
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
