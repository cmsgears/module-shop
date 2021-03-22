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
