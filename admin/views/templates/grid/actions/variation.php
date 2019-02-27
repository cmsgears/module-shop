<?php
use yii\helpers\Html;

$product = $widget->data[ 'product' ];
?>
<span title="Gallery"><?= Html::a( "", [ "variation/gallery/direct?pid=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id&pid=$product->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
