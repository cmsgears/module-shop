<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Product Variation | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget();
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-variation', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud layer layer-3">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'templateId' )->dropDownList( $templatesMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'order' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'type' )->dropDownList( $typeMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'discountType' )->dropDownList( $discountTypeMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'startDate' )->textInput( [ 'class' => 'datepicker' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'endDate' )->textInput( [ 'class' => 'datepicker' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'productId', [ 'placeholder' => 'Product', 'icon' => 'cmti cmti-search', 'url' => 'shop/product/auto-search' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'addonId', [ 'placeholder' => 'Addon', 'icon' => 'cmti cmti-search', 'url' => 'shop/product/auto-search' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud layer layer-2">
			<div class="box-header">
				<div class="box-header-title">Units & Dimensions</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'unitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'quantity' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud layer layer-1">
			<div class="box-header">
				<div class="box-header-title">Shop</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'price' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'discount' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'track', [ 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'group-target' => 'keep-stock' ], 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
					<div class="row keep-stock">
						<div class="col col2">
							<?= $form->field( $model, 'stock' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'sold' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row keep-stock">
						<div class="col col2">
							<?= $form->field( $model, 'warn' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Files</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row padding padding-small-v">
						<div class="col col3">
							<label>Banner</label>
							<?= ImageUploader::widget( [ 'model' => $banner ] ) ?>
						</div>
						<div class="col col3">
							<label>Video</label>
							<?= VideoUploader::widget( [ 'model' => $video ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Content</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Add" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
