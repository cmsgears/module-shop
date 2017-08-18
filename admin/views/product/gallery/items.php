<?php

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Product Gallery | ' . $coreProperties->getSiteTitle();

$galleryItems	= isset( $product->gallery ) ? $product->gallery->files : [];
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<div class="box box-shop-gallery box-crud">
			<div class="box-header">
				<div class="box-header-title">Product Gallery</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="card col col12x6 border border-default">
							<div class="card-header">
								<h6 class="color color-secondary-d padding padding-default"> Avatar </h6>
							</div>
							<div class="card-data padding padding-default uploader">
								<?= AvatarUploader::widget( [
										'options' => [ 'id' => 'model-avatar', 'class' => 'file-uploader' ],
										'model' => $avatar,
										'dragger' => false,
										'postAction' => true,
										'postActionUrl' => "shop/product/avatar?id=$product->id",
										'cmtApp' => 'shop',
										'cmtController' => 'product',
										'cmtAction' => 'avatar'
								] ); ?>
							</div>
						</div>
						<div class="card col col12x6 border border-default">
							<div class="card-header">
								<h6 class="color color-secondary-d padding padding-default"> Gallery </h6>
							</div>
							<div class="card-data padding padding-default uploader">
								<?= ImageUploader::widget([
                                    'options' => [ 'id' => 'model-banner', 'class' => 'file-uploader' ],
                                    'directory' => 'gallery',
									'modelClass' => 'File',
									'dragger' => false,
									'postAction' => true,
									'postActionUrl' => "shop/product/gallery/create-item?id=$id",
									'cmtApp' => 'shop',
									'cmtController' => 'gallery',
									'cmtAction' => 'addItem'
                            	]); ?>
							</div>
						</div>
					</div>
					<?php if( count( $galleryItems ) > 0 ) { ?>
					<div class="box box-basic row max-cols-100">
						<div class="cmt-slider-basic cmt-slider border border-default">
							<?php foreach( $galleryItems as $item ) { ?>
								<div class="slide">
									<img src="<?=$item->getThumbUrl() ?>">
									<div class="action align align-center" cmt-app="shop" cmt-controller="gallery" cmt-action="deleteItem" action="shop/product/gallery/delete-item?id=<?=$id?>&iid=<?=$item->id?>">
										<div class="max-area-cover spinner">
											<div class="valign-center cmti cmti-2x cmti-spinner-1 spin"></div>
										</div>
										<a><i class="cmti cmti-close-c cmti-2x valign-center cmt-click"></i></a>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>