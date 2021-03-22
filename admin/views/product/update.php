<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\shop\common\config\ShopGlobal;

use cmsgears\core\common\widgets\ActiveForm;

use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;

use cmsgears\icons\widgets\IconChooser;
use cmsgears\icons\widgets\TextureChooser;

use cmsgears\widgets\category\CategorySuggest;
use cmsgears\widgets\tag\TagMapper;
use cmsgears\widgets\elements\mappers\ElementSuggest;
use cmsgears\widgets\elements\mappers\BlockSuggest;
use cmsgears\widgets\elements\mappers\WidgetSuggest;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Product | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;

Editor::widget();
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
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'slug' ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $content, 'templateId' )->dropDownList( $templatesMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap, [ 'class' => 'cmt-select' ] ) ?>
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
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= TextureChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'reviews', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured', null, 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'order' ) ?>
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
							<?= $form->field( $model, 'primaryUnitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'primary' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'purchasingUnitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'purchase' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'quantityUnitId' )->dropDownList( $shopUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'quantity' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'weightUnitId' )->dropDownList( $weightUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'weight' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'volumeUnitId' )->dropDownList( $volumeUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'volume' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'lengthUnitId' )->dropDownList( $lengthUnitsMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2 row">
							<div class="col col2">
								<?= $form->field( $model, 'length' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'width' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'height' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'radius' ) ?>
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
							<?= $form->field( $model, 'price' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'discount' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'shop', null, 'cmti cmti-checkbox' ) ?>
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
							<label>Avatar</label>
							<?= AvatarUploader::widget( [ 'model' => $avatar ] ) ?>
						</div>
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
				<div class="box-header-title">Shop Details</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
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
							<?= $form->field( $content, 'seoName' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'seoRobot' ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= $form->field( $content, 'seoKeywords' )->textarea() ?>
						</div>
						<div class="col col2">
							<?= $form->field( $content, 'seoDescription' )->textarea() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Categories</div>
				</div>
				<div class="box-content padding padding-small">
					<?= CategorySuggest::widget([
						'model' => $model, 'type' => ShopGlobal::TYPE_PRODUCT,
						'mapActionUrl' => "$apixBase/assign-category?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-category?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
			<div class="colf colf15"></div>
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Tags</div>
				</div>
				<div class="box-content padding padding-small">
					<?= TagMapper::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-tags?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-tag?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Elements</div>
				</div>
				<div class="box-content padding padding-small">
					<?= ElementSuggest::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-element?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-element?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
			<div class="colf colf15"> </div>
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Blocks</div>
				</div>
				<div class="box-content padding padding-small">
					<?= BlockSuggest::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-block?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-block?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="row max-cols-100">
			<div class="box box-crud colf colf15x7">
				<div class="box-header">
					<div class="box-header-title">Widgets</div>
				</div>
				<div class="box-content padding padding-small">
					<?= WidgetSuggest::widget([
						'model' => $model,
						'mapActionUrl' => "$apixBase/assign-widget?slug=$model->slug&type=$model->type",
						'deleteActionUrl' => "$apixBase/remove-widget?slug=$model->slug&type=$model->type"
					])?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3"></div>
</div>
