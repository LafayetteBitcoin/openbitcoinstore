<?php if( $mime_types ) { ?>
<ul class="subsubsub">
	<?php foreach( $mime_types as $mime_type ) { ?>
	<li class="<?php echo $mime_type->filter; ?>">
		<a href="<?php echo $mime_type->filter_url; ?>"<?php if( $mime_type->current ) { ?> class="current"<?php } ?>>
			<?php echo $mime_type->post_mime_type; ?>
			<span class="count">(<?php echo $mime_type->count; ?>)</span>
		</a>
		<?php if( $mime_type->i < $size ) { ?> |<?php } ?>
	</li>
	<?php } ?>
</ul>
<?php } ?>

<form action="" method="GET">
	<table class="widefat fixed media" cellspacing="0">

		<thead>

			<tr>
				<th scope="col" id="icon" class="manage-column column-icon"></th>
				<th scope="col" id="title" class="manage-column column-title"><?php _e( 'File', 'wpsc_st' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php _e( 'Author', 'wpsc_st' ); ?></th>
				<th scope="col" id="parent" class="manage-column column-parent"><?php _e( 'Attached to', 'wpsc_st' ); ?></th>
				<th scope="col" id="date" class="manage-column column-date"><?php _e( 'Date', 'wpsc_st' ); ?></th>
			</tr>

		</thead>

		<tfoot>

			<tr>
				<th scope="col" class="manage-column column-icon"></th>
				<th scope="col" class="manage-column column-title"><?php _e( 'File', 'wpsc_st' ); ?></th>
				<th scope="col" class="manage-column column-author"><?php _e( 'Author', 'wpsc_st' ); ?></th>
				<th scope="col" class="manage-column column-parent"><?php _e( 'Attached to', 'wpsc_st' ); ?></th>
				<th scope="col" class="manage-column column-date"><?php _e( 'Date', 'wpsc_st' ); ?></th>
			</tr>

		</tfoot>

		<tbody id="the-list">

<?php if( $files ) { ?>
	<?php foreach( $files as $file ) { ?>
			<tr id="post-<?php echo $file->ID; ?>" class="author-self status-<?php echo $file->post_status; ?>" valign="top">
				<td class="column-icon media-icon">
					<!-- <a href="http://localhost/wordpress38/wp-admin/media.php?attachment_id=<?php echo $file->ID; ?>&amp;action=edit" title="<?php echo sprintf( __( 'Edit &ldquo;%s&rdquo;', 'wpsc_st' ), $file->post_title ); ?>"><?php echo $file->media_icon; ?></a> -->
					<?php echo $file->media_icon; ?>
				</td>
				<td class="title column-title">
					<strong>
						<!-- <a href="http://localhost/wordpress38/wp-admin/media.php?attachment_id=<?php echo $file->ID; ?>&amp;action=edit" title="<?php echo sprintf( __( 'Edit &ldquo;%s&rdquo;', 'wpsc_st' ), $file->post_title ); ?>"><?php echo $file->post_title; ?></a> -->
						<?php echo $file->post_title; ?>
					</strong>
					<p><?php echo $file->post_mime_type; ?></p>
					<div class="row-actions">
						<!-- <span class="edit"><a href="http://localhost/wordpress38/wp-admin/media.php?attachment_id=<?php echo $file->ID; ?>&amp;action=edit"><?php _e( 'Edit', 'wpsc_st' ); ?></a> | </span><span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="<?php echo wp_nonce_url( 'post.php?action=delete&amp;post=' . $file->ID ); ?>"><?php _e( 'Delete Permanently', 'wpsc_st' ); ?></a> | </span><span class="view"><a href="http://localhost/wordpress38/?attachment_id=<?php echo $file->ID; ?>" title="<?php echo sprintf( __( 'View &ldquo;%s&rdquo;', 'wpsc_st' ), $file->post_title ); ?>" rel="permalink"><?php _e( 'View', 'wpsc_st' ); ?></a></span> -->
						<!-- <span class="delete"><a class="submitdelete" onclick="return showNotice.warn();" href="<?php echo wp_nonce_url( 'post.php?action=delete&amp;post=' . $file->ID ); ?>"><?php _e( 'Delete Permanently', 'wpsc_st' ); ?></a></span> -->
					</div>
				</td>
				<td class="author column-author"><?php echo $file->post_author_name; ?></td>
				<td class="parent column-parent">
		<?php if( $file->post_parent ) { ?>
					<a href="<?php echo add_query_arg( array( 'post' => $file->post_parent, 'action' => 'edit' ) ); ?>"><?php echo $file->post_parent_title; ?></a>
		<?php } else if( $file->post_parent_title ) { ?>
					<span class="unassigned"><?php echo $file->post_parent_title; ?></span>
		<?php } ?>
				</td>
				<td class="date column-date"><?php echo $file->post_date; ?></td>
			</tr>

	<?php } ?>
<?php } else { ?>
			<tr id="post-<?php echo $file->ID; ?>" class="author-self status-<?php echo $file->post_status; ?>" valign="top">
				<td colspan="5" class="colspanchange"><?php _e( 'No File Downloads found.', 'wpsc_st' ); ?></td>
			</tr>
<?php } ?>
		</tbody>

	</table>
</form>