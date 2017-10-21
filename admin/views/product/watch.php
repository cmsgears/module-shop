<?php
// Yii Imports
use yii\widgets\ActiveForm;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

// SF Imports
use safaricities\shop\common\models\entities\Product;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Watch | ' . $coreProperties->getSiteTitle();

$modelContent           = $model->modelContent;
$avatar                 = $model->avatar;
$bannerUrl		= CodeGenUtil::getFileUrl( $modelContent->banner, [ 'image' => 'avatar.jpg' ] );
$avatar			= $avatar->getFileUrl() == null ? $avatar->getFileUrl() : $avatar;
$avatar			= CodeGenUtil::getThumbUrl( $avatar, [ 'image' => 'avatar-thumb.jpg' ] );
?>

<div class="watch padding padding-xlarge-h">
	<div class='card card-basic'>
		<div class='height bkg-image' style="background-image: url( <?=$bannerUrl ?> )"></div>
	</div>
	<div class="color color-primary-l">
		<div class="box box-basic row row-large">
			<div class="row">
				<div class="col col12x1">
					<img class="avatar circled circled1 padding padding-small color color-primary-l" src='<?= $avatar ?>'>
				</div>
				<div class="col col12x6">
					<div class="filler-height filler-height-small"></div>
					<h3 class="mp-none"><?=$model->name ?></h3>
				</div>
                                <?php $form = ActiveForm::begin( [ 'id' => 'frm-product' ] ); ?>
                                <div class="col col12x4">
                                <?php if( $model->isNew() || $model->isSubmitted() || $model->isReSubmit() ) { ?>
                                        <?= $form->field( $model, 'name' )->hiddenInput()->label( false ) ?>
                                        <div class="align align-center">
                                            <input type="radio" name="status" value="<?= Product::STATUS_ACTIVE ?>" checked>Approve &nbsp;&nbsp;
                                            <input type="radio" name="status" value="<?= Product::STATUS_REJECTED ?>">Reject
                                        </div>
                                        <div class="filler-height"></div>
                                        <textarea name="message" placeholder="Add cause of rejection ..."></textarea>
                                        <div class="clear filler-height"></div>
                                        <div class="align align-center">
                                                <input class="element-medium" type="submit" value="Submit" />
                                        </div>
                                        <?php } 
                                        else if( $model->isApprovable() ) { ?>
                                        <?= $form->field( $model, 'name' )->hiddenInput()->label( false ) ?>

                                        <div class="align align-center">
                                                <input type="radio" name="status" value="<?= Product::STATUS_ACTIVE ?>" checked>Approve
                                        </div>
                                        <div class="clear filler-height"></div>
                                        <div class="align align-center">
                                                <input class="element-medium" type="submit" value="Submit" />
                                        </div>
                                        <?php } else if( $model->isActive() ) { ?>
                                        <?= $form->field( $model, 'name' )->hiddenInput()->label( false ) ?>
                                        <div class="align align-center">
                                                <input type="radio" name="status" value="<?= Product::STATUS_FROJEN ?>" checked>Freeze &nbsp;&nbsp;
                                                <input type="radio" name="status" value="<?= Product::STATUS_BLOCKED ?>">Block
                                        </div>
                                        <div class="filler-height"></div>
                                        <textarea name="message" placeholder="Add cause of freeze ..."></textarea>
                                        <div class="clear filler-height"></div>
                                        <div class="align align-center">
                                                <input class="element-medium" type="submit" value="Submit" />
                                        </div>
                                        <?php } ?>
                                </div>
                                <?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>