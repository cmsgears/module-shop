<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

// CMG Imports
use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;
use cmsgears\icons\widgets\IconChooser;

use cmsgears\widgets\category\CategoryAuto;
use cmsgears\widgets\tag\TagMapper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Product | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true, 'fonts' => 'site', 'config' => [ 'controls' => 'mini' ] ] );
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-product', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col3">
							<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'slug' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'title' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $content, 'templateId' )->dropDownList( $templatesMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'startDate' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'endDate' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap', 'disabled' => true ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'publishedAt' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'reviews', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'order' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Units & Dimensions</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'primaryUnitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'primary' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'purchasingUnitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'purchase' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'quantityUnitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'quantity' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'weightUnitId' )->dropDownList( $weightUnitsMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'weight' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'volumeUnitId' )->dropDownList( $volumeUnitsMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'volume' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'lengthUnitId' )->dropDownList( $lengthUnitsMap, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
						<div class="col col2 row">
							<div class="col col2">
								<?= $form->field( $model, 'length' )->textInput( [ 'readonly' => 'true' ] ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'width' )->textInput( [ 'readonly' => 'true' ] ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'height' )->textInput( [ 'readonly' => 'true' ] ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'radius' )->textInput( [ 'readonly' => 'true' ] ) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Shop</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'price' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'discount' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'shop', [ 'disabled' => 'true' ], 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'track', [ 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'group-target' => 'keep-stock', 'disabled' => 'true' ], 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
					<div class="row keep-stock">
						<div class="col col2">
							<?= $form->field( $model, 'stock' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'sold' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row keep-stock">
						<div class="col col2">
							<?= $form->field( $model, 'warn' )->textInput( [ 'readonly' => 'true' ] ) ?>
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
							<label>Avatar</label>
							<?= AvatarUploader::widget( [ 'model' => $avatar, 'disabled' => 'true' ] ) ?>
						</div>
						<div class="col col3">
							<label>Banner</label>
							<?= ImageUploader::widget( [ 'model' => $banner, 'disabled' => 'true' ] ) ?>
						</div>
						<div class="col col3">
							<label>Video</label>
							<?= VideoUploader::widget( [ 'model' => $video, 'disabled' => 'true' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Summary</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $content, 'summary' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
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
					<?= $form->field( $content, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Page SEO</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $content, 'seoName' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'seoRobot' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $content, 'seoKeywords' )->textarea( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'seoDescription' )->textarea( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Categories</div>
				</div>
				<div class="box-content padding padding-small">
					<?= CategoryAuto::widget([
						'options' => [ 'class' => 'box-mapper-auto' ],
						'type' => ShopGlobal::TYPE_PRODUCT,
						'model' => $model, 'disabled' => true
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
						'model' => $model, 'disabled' => true
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
