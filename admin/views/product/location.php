<?php

// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Product Location | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$templates		= Yii::getAlias( "@safaricities" )."/bank/admin/views/templates";

include_once "$templates/city.php";

$spinnerHtml	= '<div class="spinner text text-tertiary-l absolute" style="display: none; top: 5px; right: 15px; z-index: 1;">
					<i class="fa fa-spinner fa-circle-o-notch spin"></i>
				</div>';
?>

<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-bank', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-content-wrap frm-split-40-60">
                <div class="box-header">
                    <div class="box-header-title">Product Location</div>
                </div>
                <div class="row">
                    <div class="col col2">
                       <?= $form->field( $model, 'line1' )->label( 'Address 1' ) ?>
                    </div>
                    <div class="col col2">
                       <?= $form->field( $model, 'line2' )->label( 'Address 2' ) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col col2">
                       <?= $form->field( $model, 'phone' ) ?>
                    </div>
                    <div class="col col2">
                       <?= $form->field( $model, 'fax' ) ?>
                    </div>
                </div>
                <div class="row">
                	<?= $form->field( $model, 'countryId', [ 'options' => [ 'class' => 'hidden' ] ] ) ?>
                   <div class="col col2 relative" cmt-app="main" cmt-controller="address" cmt-action="lgaList" action="bank/address/lga-list">
                   		<?=$spinnerHtml?>
						<?= $form->field( $model, 'provinceId' )->dropDownList( $provinceMap, [ 'class' => 'cmt-select cmt-change' ] )->label( 'State' ) ?>
					</div>
					<div class="col col2">
						<?= $form->field( $model, 'lgaId' )->dropDownList( $lgaMap, [  'id' => 'select-lga', 'class' => 'cmt-select' ] )->label( 'LGA' ) ?>
					</div>
                </div>
                <div class="row">
					<div class="col col2">
						<div class="auto-fill auto-fill-basic">
							<div class="auto-fill-source" cmt-app="main" cmt-controller="address" cmt-action="cityList" action="bank/address/city-list" cmt-keep>
								<div class="relative">
									<div class="auto-fill-search clearfix">
										<label>City</label>
										<div class="relative">
											<?=$spinnerHtml?>
											<input class="cmt-key-up auto-fill-text search-city" name="Address[cityName]" value="<?=$model->cityName ?>" placeholder="" autocomplete="off" type="text">
										</div>
										<input type="hidden" name="lga">
									</div>
									<div class="cities-list color color-primary"></div>
								</div>
							</div>
							<div class="auto-fill-target">
								<div class="form-group field-address-cityid">
									<input id="address-cityid" class="target" name="Address[cityId]" type="hidden">
									<div class="help-block"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col col2">
						<?= $form->field( $model, 'zip' )->textInput( [ 'class' => 'zip' ] ) ?>
					</div>
			</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>