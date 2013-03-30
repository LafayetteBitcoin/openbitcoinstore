<form method="post" action="<?php the_permalink(); ?>" id="your-profile">

	<h3><?php _e( 'Presentation', 'wpsc_st' ); ?></h3>

	<h4><?php _e( 'Status Indicator', 'wpsc_st' ); ?></h4>
	<p><?php _e( 'Manage the colour styles assigned to each Sale Status from the Manage Sales screen in WP e-Commerce.', 'wpsc_st' ); ?></p>
<?php if( $sale_statuses ) { ?>
	<table class="form-table">

	<?php foreach( $sale_statuses as $sale_status ) { ?>

					<tr id="status-<?php echo $sale_status['internalname']; ?>">
						<td>
							<label><strong><?php echo $sale_status['label']; ?></strong></label><br />
							<?php _e( 'Background', 'wpsc_st' ); ?>: # <input type="text" name="options[sale_status_background][<?php echo $sale_status['internalname']; ?>]" size="6" value="<?php echo $sale_status_background[$sale_status['internalname']]; ?>" />
							<?php _e( 'Border', 'wpsc_st' ); ?>: # <input type="text" name="options[sale_status_border][<?php echo $sale_status['internalname']; ?>]" size="6" value="<?php echo $sale_status_border[$sale_status['internalname']]; ?>" />
							<p class="description"><?php echo sprintf( __( 'Default background is: %s, with a border of: %s', 'wpsc_st' ), '<code>' . $sale_status['default_background'] . '</code>', '<code>' . $sale_status['default_border'] . '</code>' ); ?>
						</td>
					</tr>

	<?php } ?>

	</table>
	<p class="description"><?php _e( 'Customise the colours for each Sale Status.', 'wpsc_st' ); ?></p>
<?php } ?>

	<h4><?php _e( 'Buttons', 'wpsc_st' ); ?></h4>
	<p><?php _e( 'Change the button text on button elements on the Products Page and Single Product screens.', 'wpsc_st' ); ?></p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="addtocart-label"><?php _e( 'Add To Cart', 'wpsc_pe' ); ?>:</label></th>
			<td>
				<input type="text" id="addtocart-label" name="options[addtocart_label]" value="<?php echo $options['addtocart_label']; ?>" />
				<p class="description"><?php echo sprintf( __( 'Default: %s', 'wpsc_st' ), '<code>' . __( 'Add To Cart', 'wpsc' ) . '</code>' ); ?></p>
			</td>
		</tr>
	</table>
	<p class="description"><?php _e( 'Due to limitations in detecting Buy Now vs. Add To Cart, any Buy Now instances will be replaced with the label above.', 'wpsc_st' ); ?></p>

	<p class="submit">
		<input type="submit" value="<?php _e( 'Save Changes', 'wpsc_st' ); ?>" class="button-primary" />
	</p>
	<input type="hidden" name="action" value="update" />

</form>

<form method="post" action="<?php the_permalink(); ?>" class="nuke" id="uninstall-wpecommerce">
	<div>
		<h3><?php _e( 'Uninstall WP e-Commerce', 'wpsc_st' ); ?></h3>
		<p><?php _e( 'Remove all traces of WP e-Commerce from the WordPress database as well as physical directories created by WP e-Commerce, this includes:', 'wpsc_st' ); ?></p>
		<ul class="ul-disc">
			<li><?php _e( 'All WordPress tables prefixed by wpsc_*', 'wpsc_st' ); ?></li>
			<li><?php _e( 'All Terms and Custom Post Types associated with WP e-Commerce', 'wpsc_st' ); ?></li>
			<li><?php _e( 'All directories within Uploads (/wp-content/uploads/...) created by WP e-Commerce', 'wpsc_st' ); ?></li>
			<li><?php _e( 'The \'wp-e-commerce\' directory within Plugins (/wp-content/plugins/...)', 'wpsc_st' ); ?></li>
		</ul>
		<p><?php _e( 'Put bluntly, anything related to WP e-Commerce that existed before this point will not survive.', 'wpsc_st' ); ?>
		<p class="submit">
<?php if( function_exists( 'wpsc_find_purchlog_status_name' ) && wpsc_get_major_version() == '3.8' ) { ?> 
			<input type="button" class="button button-disabled" value="<?php _e( 'Uninstall WP e-Commerce', 'wpsc_st' ); ?>" />
<?php } else { ?>
			<input type="submit" value="<?php _e( 'Uninstall WP e-Commerce', 'wpsc_st' ); ?>" class="button-primary" />
			<input type="hidden" name="action" value="uninstall" />
<?php } ?>
		</p>
		<p class="description"><?php _e( '* WP e-Commerce must be de-activated to ensure there are no Plugin conflicts, only then will the Nuke button become available.', 'wpsc_st' ); ?></p>
	</div>
</form>