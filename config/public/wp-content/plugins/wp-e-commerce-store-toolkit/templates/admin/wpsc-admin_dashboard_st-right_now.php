<div class="table table_content">
	<p class="sub"><?php _e( 'Catalogue', 'wpsc_st' ); ?></p>
	<table>
		<tr class="first">
			<td class="first b b-posts"><a href="edit.php?post_type=wpsc-product"><?php echo $products; ?></a></td>
			<td class="t posts"><a href="edit.php?post_type=wpsc-product"><?php _e( 'Products', 'wpsc_st' ); ?></a></td>
		</tr>
		<tr>
			<td class="first b b_pages"><a href="edit-tags.php?taxonomy=wpsc-variation&post_type=wpsc-product"><?php echo $variations; ?></a></td>
			<td class="t pages"><a href="edit-tags.php?taxonomy=wpsc-variation&post_type=wpsc-product"><?php _e( 'Variations', 'wpsc_st' ); ?></a></td>
		</tr>
		<tr>
			<td class="first b b_pages"><a href="edit-tags.php?taxonomy=wpsc_product_category&post_type=wpsc-product"><?php echo $categories; ?></a></td>
			<td class="t pages"><a href="edit-tags.php?taxonomy=wpsc_product_category&post_type=wpsc-product"><?php _e( 'Categories', 'wpsc_st' ); ?></a></td>
		</tr>
		<tr>
			<td class="first b b-cats"><a href="edit-tags.php?taxonomy=product_tag&post_type=wpsc-product"><?php echo $tags; ?></a></td>
			<td class="t cats"><a href="edit-tags.php?taxonomy=product_tag&post_type=wpsc-product"><?php _e( 'Tags', 'wpsc_st' ); ?></a></td>
		</tr>
<?php if( isset( $attributes ) ) { ?>
		<tr>
			<td class="first b b-tags"><a href="edit.php?post_type=wpsc-product&page=wpsc_cf"><?php echo $attributes; ?></a></td>
			<td class="t tags"><a href="edit.php?post_type=wpsc-product&page=wpsc_cf"><?php _e( 'Attributes', 'wpsc_st' ); ?></a></td>
		</tr>
<?php } ?>
		<tr>
			<td class="first b b-tags"><a href="edit.php?post_type=wpsc-product&page=wpsc-edit-coupons"><?php echo $coupons; ?></a></td>
			<td class="t tags"><a href="edit.php?post_type=wpsc-product&page=wpsc-edit-coupons"><?php _e( 'Coupons', 'wpsc_st' ); ?></a></td>
		</tr>
	</table>
</div>
<div class="table table_discussion">
	<p class="sub"><?php _e( 'Sales', 'wpsc_st' ); ?></p>
	<table>
		<tr class="first">
			<td class="b b-comments"><a href="index.php?page=wpsc-purchase-logs"><span class="total-count"><?php echo $sales_overall; ?></span></a></td>
			<td class="last t comments"><a href="index.php?page=wpsc-purchase-logs"><?php _e( 'Sales', 'wpsc_st' ); ?></a></td>
		</tr>
		<tr>
			<td class="b b_approved"><a href="index.php?page=wpsc-purchase-logs&status=3"><span class="approved-count"><?php echo $sales_approved; ?></span></a></td>
			<td class="last t"><a class='approved' href="index.php?page=wpsc-purchase-logs&status=3"><?php _e( 'Approved', 'wpsc_st' ); ?></a></td>
		</tr>
		<tr>
			<td class="b b-waiting"><a href="index.php?page=wpsc-purchase-logs&status=2"><span class="pending-count"><?php echo $sales_pending; ?></span></a></td>
			<td class="last t"><a class='waiting' href="index.php?page=wpsc-purchase-logs&status=2"><?php _e( 'Pending', 'wpsc_st' ); ?></a></td>
		</tr>
		<tr>
			<td class="b b-spam"><a href="index.php?page=wpsc-purchase-logs&status=5"><span class='spam-count'><?php echo $sales_declined; ?></span></a></td>
			<td class="last t"><a class='spam' href="index.php?page=wpsc-purchase-logs&status=5"><?php _e( 'Declined', 'wpsc_st' ); ?></a></td>
		</tr>
<?php if( isset( $sales_refunded ) ) { ?>
		<tr>
			<td class="b b-comments"><a href="index.php?page=wpsc-purchase-logs&status=6"><span class='spam-count'><?php echo $sales_refunded; ?></span></a></td>
			<td class="last t"><a class='spam' href="index.php?page=wpsc-purchase-logs&status=6"><?php _e( 'Refunded', 'wpsc_st' ); ?></a></td>
		</tr>
<?php } ?>
	</table>
</div>
<div class="versions">
	<span id='wp-version-message'>You are using <span class="b">WP e-Commerce <?php echo WPSC_VERSION; ?></span>.</span>
	<br class="clear" />
</div>