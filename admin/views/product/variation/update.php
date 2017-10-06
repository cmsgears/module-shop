<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Variation | ' . $coreProperties->getSiteTitle();

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true, 'fonts' => 'site', 'config' => [ 'controls' => 'mini' ] ] );
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-page', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Variation Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col1">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'quantity' ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'type' )->dropDownList( $typeMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'value' ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'startDate' )->textInput( [ 'class' => 'date' ] ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'endDate' )->textInput( [ 'class' => 'date' ] ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'active' )->checkbox( [], false ) ?>
						</div>
						<div class="col col1">
							<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'View All', [ "all?pid=$product->id" ], [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
