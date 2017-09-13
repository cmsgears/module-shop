<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\widgets\Editor;
use cmsgears\widgets\category\CategoryAuto;
use cmsgears\widgets\tag\TagMapper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Setting | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true, 'fonts' => 'site', 'config' => [ 'controls' => 'mini' ] ] );
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-page', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Product Setting</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col1">
							<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap, [ 'class' => 'cmt-select' ] ) ?>
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="box-header">
						<div class="box-header-title">SEO</div>
					</div>
					<div class="row">
						<div class="col col1">
							<?= $form->field( $content, 'seoName' )?>
							<?= $form->field( $content, 'seoRobot' )?>
							<?= $form->field( $content, 'seoDescription' )->textarea() ?>
							<?= $form->field( $content, 'seoKeywords' )->textarea() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Categories</div>
				</div>
				<div class="box-content padding padding-small">
					<?= CategoryAuto::widget([
						'options' => [ 'class' => 'box-mapper-auto' ],
						'type' => ShopGlobal::TYPE_PRODUCT,
						'model' => $model, 'app' => 'category',
						'mapActionUrl' => "shop/shop/assign-category?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "shop/shop/remove-category?slug=$model->slug&type=$model->type"
					]) ?>
				</div>
			</div>
			<div class="colf colf15"></div>
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Tags</div>
				</div>
				<div class="box-content padding padding-small">
					<?= TagMapper::widget([
						'options' => [ 'id' => 'box-tag-mapper', 'class' => 'box-tag-mapper' ],
						'loadAssets' => true,
						'model' => $model, 'app' => 'category',
						'mapActionUrl' => "shop/shop/assign-tags?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "shop/shop/remove-tag?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
