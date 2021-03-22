<div class="box box-crud">
	<div class="box-header">
		<div class="box-header-title">Shop Details</div>
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
					<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'shop', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
				</div>
				<div class="col col2">
					<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'track', [ 'disabled' => true, 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'group-target' => 'keep-stock' ], 'cmti cmti-checkbox' ) ?>
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
