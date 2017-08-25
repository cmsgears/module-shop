<?php
use yii\helpers\Html;
?>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
<span title="Gallery"><?= Html::a( "", [ "product/gallery/index?pid=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Location"><?= Html::a( "", [ "product/location?id=$model->id" ], [ 'class' => 'cmti cmti-marker' ] ) ?></span>
<span title="Attributes"><?= Html::a( "", [ "product/meta/all?id=$model->id" ], [ 'class' => 'cmti cmti-tag' ] ) ?></span>
<span title="Setting"><?= Html::a( "", [ "product/setting?id=$model->id" ], [ 'class' => 'cmti cmti-setting' ] ) ?></span>
<span title="Shop"><?= Html::a( "", [ "product/shop?id=$model->id" ], [ 'class' => 'cmti cmti-cart' ] ) ?></span>
<span title="Variations"><?= Html::a( "", [ "product/variation/all?pid=$model->id" ], [ 'class' => 'cmti cmti-asterisk' ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
