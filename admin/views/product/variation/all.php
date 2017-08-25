<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Product Variations | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-shop/admin/views/templates';

$productId			= $product->id;
?>

<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?id=$productId", 'data' => [ 'productId' => $productId ],
	'title' => 'Products', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name' ],
	'sortColumns' => [
		'name' => 'Name'
	],
	'filters' => [ 'status' => [ 'active' => 'Active' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null , 'x3', 'x3', 'x4', 'x3', null  ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'quantity' => 'Qty',
		'type' => [ 'title' => 'Type', 'generate' => function( $model ) { return $model->getTypeStr(); } ],
		'value' => 'Value',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/generic",
	//'cardView' => "$moduleTemplates/grid/cards/generic",
	'actionView' => "$moduleTemplates/grid/actions/generic"
]) ?>

<?= Popup::widget([
	'title' => 'Update Variation', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Attribute', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "shop/product/variation/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Variation', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Product', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "shop/product/variation/delete?id=" ]
]) ?>
