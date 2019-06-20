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

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * ProductController provides actions specific to product model.
 *
 * @since 1.0.0
 */
class ProductController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;
	public $reviews;

	public $metaService;

	// Protected --------------

	protected $type;
	protected $templateType;
	protected $prettyReview;

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

		// Config
		$this->type			= CoreGlobal::TYPE_DEFAULT;
		$this->templateType	= ShopGlobal::TYPE_PRODUCT;
		$this->title		= 'Product';
		$this->baseUrl		= 'product';
		$this->apixBase		= 'shop/product';
		$this->reviews		= true;
		$this->prettyReview	= false;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'productService' );
		$this->metaService		= Yii::$app->factory->get( 'productMetaService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

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
			'review' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Review' ] ],
			'gallery' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Gallery' ] ],
			'data' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'config' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Products', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'review' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'gallery' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'data' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'attributes' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'config' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'settings' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'review' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'gallery' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'data' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'attributes' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'config' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'settings' ] = [ 'get', 'post' ];

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

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\cms\common\actions\regular\gallery\Browse' ],
			'data' => [ 'class' => 'cmsgears\cms\common\actions\data\data\Form' ],
			'attributes' => [ 'class' => 'cmsgears\cms\common\actions\data\attribute\Form' ],
			'config' => [ 'class' => 'cmsgears\cms\common\actions\data\config\Form' ],
			'settings' => [ 'class' => 'cmsgears\cms\common\actions\data\setting\Form' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProductController ---------------------

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
				'admin' => true, 'content' => $content,
				'avatar' => $avatar, 'banner' => $banner, 'video' => $video
			]);

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

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
				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'content' => $content,
					'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

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

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

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

					case $modelClass::STATUS_ACCEPTED: {

						$this->modelService->accept( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendAcceptMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_SUBMITTED: {

						$this->modelService->approve( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendApproveMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_REJECTED: {

						$model->setRejectMessage( $message );
						$model->refresh();

						$this->modelService->reject( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendRejectMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_FROJEN: {

						$model->setRejectMessage( $message );
						$model->refresh();

						$this->modelService->freeze( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendFreezeMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_BLOCKED: {

						$model->setRejectMessage( $message );
						$model->refresh();

						$this->modelService->block( $model, [ 'notify' => false ] );

						Yii::$app->coreMailer->sendBlockMail( $model, $email, $message );

						break;
					}
					case $modelClass::STATUS_ACTIVE: {

						$this->modelService->activate( $model, [ 'notify' => false ] );

						$model->updateDataMeta( CoreGlobal::DATA_APPROVAL_REQUEST, false );

						Yii::$app->coreMailer->sendActivateMail( $model, $email );
					}
				}

				$this->redirect( $this->returnUrl );
			}

			$content	= $model->modelContent;
			$template	= $content->template;

			if( $this->prettyReview && isset( $template ) ) {

				return Yii::$app->templateManager->renderViewAdmin( $template, [
					'model' => $model,
					'content' => $content,
					'metaService' => $this->metaService,
					'userReview' => false,
					'adminReview' => true
				], [ 'layout' => false ] );
			}
			else {

				$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

				$shopUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_QUANTITY, Uom::GROUP_WEIGHT_US ] );
				$weightUnitsMap	= $this->uomService->getIdNameMapByGroups( [ Uom::GROUP_WEIGHT_METRIC, Uom::GROUP_WEIGHT_US ] );
				$volumeUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_VOLUME_US );
				$lengthUnitsMap	= $this->uomService->getIdNameMapByGroup( Uom::GROUP_LENGTH_US );

				return $this->render( 'review', [
					'modelService' => $this->modelService,
					'metaService' => $this->metaService,
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
