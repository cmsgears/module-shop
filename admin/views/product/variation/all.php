<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Product Attributes | ' . $coreProperties->getSiteTitle();

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
		'label' => 'Label',
		'value' => 'Value',
		'valueType' => 'Value Type',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/gallery",
	//'cardView' => "$moduleTemplates/grid/cards/gallery",
	'actionView' => "$moduleTemplates/grid/actions/meta"
]) ?>

<?= Popup::widget([
	'title' => 'Update Attribute', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Attribute', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "shop/product/meta/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Attribute', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Product', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "shop/product/meta/delete?id=" ]
]) ?>
