<h3><?php _e( 'Versions', 'wpsc_st' ); ?></h3>
<table class="widefat fixed">

	<tr>
		<th><?php _e( 'WordPress', 'wpsc_st' ); ?></th>
		<td><?php echo get_bloginfo( 'version' ); ?></td>
	</tr>

	<tr>
		<th><?php _e( 'WP e-Commerce', 'wpsc_st' ); ?></th>
		<td><?php echo WPSC_VERSION; ?></td>
	</tr>

	<tr>
		<th><?php _e( 'Gold Cart', 'wpsc_st' ); ?></th>
		<td>
			<?php echo $gold_cart; ?>
		</td>
	</tr>

	<tr>
		<th><?php _e( 'Theme', 'wpsc_st' ); ?></th>
		<td>
			<?php echo $theme['name']; ?> (by <a href="<?php echo $theme['author_url']; ?>" target="_blank"><?php echo $theme['author']; ?></a>) Version: <?php echo $theme['version']; ?>
		</td>
	</tr>

	<tr>
		<th><?php _e( 'Active Plugins', 'wpsc_st' ); ?>:</th>
		<td>
<?php if( $active_plugins ) { ?>
			<ul>
	<?php foreach( $active_plugins as $active_plugin ) { ?>
				<li><a href="<?php echo $active_plugin['url']; ?>"><?php echo $active_plugin['name']; ?></a> (by <a href="<?php echo $active_plugin['author_url']; ?>" target="_blank"><?php echo $active_plugin['author']; ?></a>): Version <?php echo $active_plugin['version']; ?></li>
	<?php } ?>
			</ul>
<?php } ?>
		</td>
	</tr>

</table>

<h3><?php _e( 'Server Environment', 'wpsc_st' ); ?></h3>
<table class="widefat fixed">

	<tr>
		<th><?php _e( 'PHP Version', 'wpsc_st' ); ?></th>
		<td><?php echo PHP_VERSION; ?></td>
	</tr>

	<tr>
		<th><?php _e( 'MySQL Version', 'wpsc_st' ); ?></th>
		<td><?php echo mysql_get_server_info(); ?></td>
	</tr>
	

	<tr>
		<th><?php _e( 'Max. Upload Filesize', 'wpsc_st' ); ?></th>
		<td><?php echo $max_upload; ?></td>
	</tr>

	<tr>
		<th><?php _e( 'Max. POST Size', 'wpsc_st' ); ?></th>
		<td><?php echo $max_post; ?></td>
	</tr>

	<tr>
		<th><?php _e( 'Memory Allocation', 'wpsc_st' ); ?></th>
		<td><?php echo $memory_limit; ?></td>
	</tr>

</table>

<h3><?php _e( 'Pages', 'wpsc_st' ); ?></h3>
<?php if( $wpsc_pages ) { ?>
<table class="widefat fixed">

	<?php foreach( $wpsc_pages as $wpsc_page ) { ?>
	<tr>
		<th><?php echo $wpsc_page['title']; ?></th>
		<td>
			<?php echo $wpsc_page['url']; ?>
			<!-- (#<?php echo $wpsc_page['post_ID']; ?>) -->
		</td>
	</tr>
	<?php } ?>

</table>
<?php } ?>

<h3><?php _e( 'Storefront', 'wpsc_st' ); ?></h3>
<table class="widefat fixed">

	<tr>
		<th><?php _e( 'Products', 'wpsc_st' ); ?></th>
		<td>
			<?php echo wpsc_st_return_count( 'products' ); ?>
		</td>
	</tr>

	<tr>
		<th><?php _e( 'Product Variations', 'wpsc_st' ); ?></th>
		<td>
			<?php echo wpsc_st_return_count( 'variations' ); ?>
		</td>
	</tr>

	<tr>
		<th><?php _e( 'Product Images', 'wpsc_st' ); ?></th>
		<td>
			<?php echo wpsc_st_return_count( 'images' ); ?>
		</td>
	</tr>

	<tr>
		<th><?php _e( 'Download Files', 'wpsc_st' ); ?></th>
		<td>
			<?php echo wpsc_st_return_count( 'files' ); ?>
		</td>
	</tr>

</table>