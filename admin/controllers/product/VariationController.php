<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\admin\controllers\product;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\resources\File;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\admin\controllers\base\Controller;

/**
 * VariationController provide actions specific to product variations.
 *
 * @since 1.0.0
 */
class VariationController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $templateService;

	protected $productService;

	protected $uomService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-shop/admin/views/product/variation' );

		// Permission
		$this->crudPermission = ShopGlobal::PERM_PRODUCT_ADMIN;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'productVariationService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->productService	= Yii::$app->factory->get( 'productService' );

		$this->uomService = Yii::$app->factory->get( 'uomService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product' ];

		// Return Url
		$this->returnUrl = Url::previous( 'product-variations' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/variation/all/' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Products', 'url' =>  [ '/shop/product/all' ] ] ],
			'all' => [ [ 'label' => 'Variations' ] ],
			'create' => [ [ 'label' => 'Variations', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Variations', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Variations', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'index'	 => [ 'permission' => $this->crudPermission ],
					'all'  => [ 'permission' => $this->crudPermission ],
					'create'  => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ],
					'delete'  => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all'  => [ 'get' ],
					'create'  => [ 'get', 'post' ],
					'update'  => [ 'get', 'post' ],
					'delete'  => [ 'get', 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// VariationController -------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'product-variations' );

		$product = $this->productService->getById( $pid );

		if( isset( $product ) ) {

			$modelClass = $this->modelService->getModelClass();

			$dataProvider = $this->modelService->getPageByProductId( $product->id );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'product' => $product,
				'typeMap' => $modelClass::$typeMap,
				'discountTypeMap' => $modelClass::$discountTypeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid ) {

		$product = $this->productService->getById( $pid );

		if( isset( $product ) ) {

			$modelClass	= $this->modelService->getModelClass();

			$model = new $modelClass;

			$model->productId = $product->id;

			$banner	 = File::loadFile( null, 'Banner' );
			$video	 = File::loadFile( null, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->create( $model, [ 'admin' => true, 'banner' => $banner, 'video' => $video ] );

				return $this->redirect( "all?pid={$product->id}" );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( ShopGlobal::TYPE_PRODUCT, [ 'default' => true ] );
			$shopUnitsMap = $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );

			return $this->render( 'create', [
				'model' => $model,
				'banner' => $banner,
				'video' => $video,
				'product' => $product,
				'typeMap' => $modelClass::$typeMap,
				'discountTypeMap' => $modelClass::$discountTypeMap,
				'templatesMap' => $templatesMap,
				'shopUnitsMap' => $shopUnitsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $pid ) {

		$model = $this->modelService->getById( $id );

		$product = $this->productService->getById( $pid );

		if( isset( $model ) && isset( $product ) ) {

			$modelClass	= $this->modelService->getModelClass();

			$banner	 = File::loadFile( $model->banner, 'Banner' );
			$video	 = File::loadFile( $model->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model );

				return $this->refresh();
			}

			$templatesMap = $this->templateService->getIdNameMapByType( ShopGlobal::TYPE_PRODUCT, [ 'default' => true ] );
			$shopUnitsMap = $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );

			return $this->render( 'update', [
				'model' => $model,
				'banner' => $banner,
				'video' => $video,
				'product' => $product,
				'typeMap' => $modelClass::$typeMap,
				'discountTypeMap' => $modelClass::$discountTypeMap,
				'templatesMap' => $templatesMap,
				'shopUnitsMap' => $shopUnitsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $pid ) {

		$model = $this->modelService->getById( $id );

		$product = $this->productService->getById( $pid );

		if( isset( $model ) && isset( $product ) ) {

			$modelClass	= $this->modelService->getModelClass();

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$templatesMap = $this->templateService->getIdNameMapByType( ShopGlobal::TYPE_PRODUCT, [ 'default' => true ] );
			$shopUnitsMap = $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );

			return $this->render( 'delete', [
				'model' => $model,
				'banner' => $model->banner,
				'video' => $model->video,
				'product' => $product,
				'typeMap' => $modelClass::$typeMap,
				'discountTypeMap' => $modelClass::$discountTypeMap,
				'templatesMap' => $templatesMap,
				'shopUnitsMap' => $shopUnitsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}