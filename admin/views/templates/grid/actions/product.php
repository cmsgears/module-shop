<?php
use yii\helpers\Html;
?>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
<span title="Gallery"><?= Html::a( "", [ "product/gallery/index?pid=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Location"><?= Html::a( "", [ "product/location?id=$model->id" ], [ 'class' => 'cmti cmti-marker' ] ) ?></span>
<span title="Attributes"><?= Html::a( "", [ "product/attributes/all?id=$model->id" ], [ 'class' => 'cmti cmti-tag' ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
