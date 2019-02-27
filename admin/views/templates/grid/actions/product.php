<?php
use yii\helpers\Html;
?>
<span title="Variations"><?= Html::a( "", [ "product/variation/all?pid=$model->id" ], [ 'class' => 'cmti cmti-plans' ] ) ?></span>
<span title="Reviews"><?= Html::a( "", [ "product/review/all?pid=$model->id" ], [ 'class' => 'cmti cmti-comment' ] ) ?></span>
<span title="Gallery"><?= Html::a( "", [ "product/gallery/direct?pid=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Attributes"><?= Html::a( "", [ "product/attribute/all?pid=$model->id" ], [ 'class' => 'cmti cmti-tag' ] ) ?></span>
<span title="Review"><?= Html::a( "", [ "review?id=$model->id" ], [ 'class' => 'cmti cmti-eye' ] )  ?></span>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
