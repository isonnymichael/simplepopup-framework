<!-- The Modal -->
<?php foreach ( $simplepopup_to_display as $item ) : ?>

<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div style="display:flex;justify-content: space-between">
            <h3 style="margin: 0px"><?php echo esc_attr( $item->getTitle() ); ?></h3>
            <span class="close">&times;</span>
        </div>
        <div>
			<?php echo  $item->getContent(); ?>
        </div>
    </div>

</div>
<?php endforeach; ?>
