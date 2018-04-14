<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\shop\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\models\resources\File;
use cmsgears\cart\common\models\resources\Uom;

use cmsgears\core\admin\controllers\base\CrudController;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * ProductController provides actions specific to product model.
 *
 * @since 1.0.0
 */
class ProductController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $apixBase = 'shop/product';

	public $metaService;

	// Protected --------------

	protected $templateService;

	protected $modelContentService;
	protected $modelCategoryService;

	protected $uomService;

	// Private------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// View Path
		$this->setViewPath( '@cmsgears/module-shop/admin/views/product' );

		// Permission
		$this->crudPermission = ShopGlobal::PERM_PRODUCT_ADMIN;

		// Services
		$this->modelService			= Yii::$app->factory->get( 'productService' );
		$this->metaService			= Yii::$app->factory->get( 'productMetaService' );
		$this->templateService		= Yii::$app->factory->get( 'templateService' );

		$this->modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$this->modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );

		$this->uomService = Yii::$app->factory->get( 'uomService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-shop', 'child' => 'product' ];

		// Return Url
		$this->returnUrl = Url::previous( 'products' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/shop/product/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'all' => [ [ 'label' => 'Products' ] ],
			'create' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'review' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Review' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'review' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'review' ] = [ 'get', 'post' ];

		$behaviors[ 'activity' ] = [
			'class' => ActivityBehavior::class,
			'admin' => true,
			'create' => [ 'create' ],
			'update' => [ 'update' ],
			'delete' => [ 'delete' ]
		];

		return $behaviors;
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ElementController ---------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'products' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= CoreGlobal::TYPE_DEFAULT;
		$model->reviews	= true;

		$content = $this->modelContentService->getModelObject();

		$avatar	 = File::loadFile( null, 'Avatar' );
		$banner	 = File::loadFile( null, 'Banner' );
		$video	 = File::loadFile( null, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
			$model->validate() && $content->validate() ) {

			$this->model = $this->modelService->add( $model, [
				'admin' => true,
				'avatar' => $avatar, 'banner' => $banner, 'video' => $video,
				'content' => $content
			]);

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( ShopGlobal::TYPE_PRODUCT, [ 'default' => true ] );

		$shopUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );
		$weightUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_WEIGHT_METRIC, Uom::GROUP_WEIGHT_US ] );
		$volumeUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_VOLUME_US );
		$lengthUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_LENGTH_US );

		return $this->render( 'create', [
			'model' => $model,
			'content' => $content,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap,
			'templatesMap' => $templatesMap,
			'shopUnitsMap' => $shopUnitsMap,
			'weightUnitsMap' => $weightUnitsMap,
			'volumeUnitsMap' => $volumeUnitsMap,
			'lengthUnitsMap' => $lengthUnitsMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$content = $model->modelContent;

			$avatar	 = File::loadFile( $model->avatar, 'Avatar' );
			$banner	 = File::loadFile( $content->banner, 'Banner' );
			$video	 = File::loadFile( $content->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
				$model->validate() && $content->validate() ) {

				// Update product
				$this->model = $this->modelService->update( $model, [ 'admin' => true, 'avatar' => $avatar ] );

				// Update model content
				$this->modelContentService->update( $content, [ 'publish' => true, 'banner' => $banner, 'video' => $video ] );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( ShopGlobal::TYPE_PRODUCT, [ 'default' => true ] );

			$shopUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );
			$weightUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_WEIGHT_METRIC, Uom::GROUP_WEIGHT_US ] );
			$volumeUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_VOLUME_US );
			$lengthUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_LENGTH_US );

			return $this->render( 'update', [
				'model' => $model,
				'content' => $content,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap,
				'shopUnitsMap' => $shopUnitsMap,
				'weightUnitsMap' => $weightUnitsMap,
				'volumeUnitsMap' => $volumeUnitsMap,
				'lengthUnitsMap' => $lengthUnitsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$content = $model->modelContent;

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

			$shopUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );
			$weightUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_WEIGHT_METRIC, Uom::GROUP_WEIGHT_US ] );
			$volumeUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_VOLUME_US );
			$lengthUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_LENGTH_US );

			return $this->render( 'delete', [
				'model' => $model,
				'content' => $content,
				'avatar' => $model->avatar,
				'banner' => $content->banner,
				'video' => $content->video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap,
				'shopUnitsMap' => $shopUnitsMap,
				'weightUnitsMap' => $weightUnitsMap,
				'volumeUnitsMap' => $volumeUnitsMap,
				'lengthUnitsMap' => $lengthUnitsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionReview( $id ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$status		= Yii::$app->request->post( 'status' );
				$email		= $model->creator->email;
				$message	= Yii::$app->request->post( 'message' );

				switch( $status ) {

					case $modelClass::STATUS_SUBMITTED: {

						$this->modelService->approve( $model, $message );

						Yii::$app->coreMailer->sendApproveMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_REJECTED: {

						$this->modelService->reject( $model, $message );

						Yii::$app->coreMailer->sendRejectMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_FROJEN: {

						$this->modelService->freeze( $model, $message );

						Yii::$app->coreMailer->sendFreezeMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_BLOCKED: {

						$this->modelService->block( $model, $message );

						Yii::$app->coreMailer->sendBlockMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_ACTIVE: {

						$this->modelService->activate( $model );

						$model->updateDataMeta( CoreGlobal::DATA_APPROVAL_REQUEST, false );

						Yii::$app->coreMailer->sendActivateMail( $model, $email );
					}
				}

				$this->redirect( $this->returnUrl );
			}

			$content	= $model->modelContent;
			$template	= $content->template;

			if( isset( $template ) ) {

				return Yii::$app->templateManager->renderViewAdmin( $template, [
					'model' => $model,
					'content' => $content,
					'userReview' => false
				], [ 'layout' => false ] );
			}
			else {

				$templatesMap = $this->templateService->getIdNameMapByType( ShopGlobal::TYPE_PRODUCT, [ 'default' => true ] );

				$shopUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );
				$weightUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_WEIGHT_METRIC, Uom::GROUP_WEIGHT_US ] );
				$volumeUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_VOLUME_US );
				$lengthUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_LENGTH_US );

				return $this->render( 'review', [
					'model' => $model,
					'content' => $content,
					'avatar' => $model->avatar,
					'banner' => $content->banner,
					'video' => $content->video,
					'visibilityMap' => $modelClass::$visibilityMap,
					'statusMap' => $modelClass::$statusMap,
					'templatesMap' => $templatesMap,
					'shopUnitsMap' => $shopUnitsMap,
					'weightUnitsMap' => $weightUnitsMap,
					'volumeUnitsMap' => $volumeUnitsMap,
					'lengthUnitsMap' => $lengthUnitsMap
				]);
			}
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
