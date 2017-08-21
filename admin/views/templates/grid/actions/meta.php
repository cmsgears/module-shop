<?php
use yii\helpers\Html;

$productId	= $widget->data[ 'productId' ];
?>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id&pid=$productId" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>
<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
