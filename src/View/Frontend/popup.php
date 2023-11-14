
<?php foreach ( $simplepopup_to_display as $item ) : ?>

<div id="simplepopup-<?php echo $item->getID() ?>" class="simplepopup <?php echo 'simplepopup-'.$item->getDisplay() ?>">
	<div class="simplepopup-wrapper <?php echo esc_attr( $item->getSize() ); ?>">
		<div class="simplepopup-header">
			<h3><?php echo esc_attr( $item->getTitle() ); ?></h3>
			<span class="close simplepopup-close-<?php echo $item->getID() ?>">&times;</span>
		</div>
		<div class="simplepopup-content-wrapper">
			<div class="simplepopup-content">
				<?php echo  $item->getContent(); ?>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
