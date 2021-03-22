<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\core\common\widgets\Editor;
use cmsgears\widgets\popup\Popup;

Editor::widget();

// Config -------------------------

$coreProperties = $this->context->getCoreProperties();
$siteProperties	= $this->context->getSiteProperties();
$title			= $this->context->title;
$this->title	= "Review {$title} | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;
$modelContent	= $model->modelContent;

$reviewIncludes = Yii::getAlias( '@cmsgears' ) . '/module-shop/admin/views/includes/review';

// Services -----------------------

$categoryService	= Yii::$app->factory->get( 'categoryService' );
$optionService		= Yii::$app->factory->get( 'optionService' );
$tagService			= Yii::$app->factory->get( 'tagService' );

// Approval -----------------------

$modelClass	= $modelService->getModelClass();

// Basic --------------------------

// Units --------------------------

// Shop ---------------------------

// Attributes ---------------------

$metas = $model->getMetasByType( CoreGlobal::META_TYPE_USER );

// Files --------------------------

$gallery		= $modelContent->gallery;
$galleryFiles	= isset( $gallery ) ? $gallery->modelFiles : [];

// Settings -----------------------

?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main margin margin-small">
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/product/approval.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-product', 'options' => [ 'class' => 'form' ] ] ); ?>
		<?php include "$reviewIncludes/product/basic.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/product/units.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/product/shop.php"; ?>
		<?php if( count( $metas ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<?php include "$reviewIncludes/product/attributes.php"; ?>
		<?php } ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/product/files.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/product/content.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php include "$reviewIncludes/product/seo.php"; ?>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
<?= Popup::widget([
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/popup/lightbox' ), 'template' => 'slider'
])?>
