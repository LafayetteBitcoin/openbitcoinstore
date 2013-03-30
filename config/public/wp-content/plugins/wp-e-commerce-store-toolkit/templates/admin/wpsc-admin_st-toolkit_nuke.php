<ul class="subsubsub">
	<li><a href="#empty-wpecommerce-tables"><?php _e( 'Empty WP e-Commerce Tables', 'wpsc_st' ); ?></a> |</li>
	<li><a href="#empty-3rdparty-plugins"><?php _e( 'Empty 3rd Party Plugins', 'wpsc_st' ); ?></a> |</li>
	<li><a href="#empty-product-by-category"><?php _e( 'Empty Products by Product Category', 'wpsc_st' ); ?></a> |</li>
	<li><a href="#empty-wordpress-tables"><?php _e( 'Empty WordPress Tables', 'wpsc_st' ); ?></a></li>
</ul>
<br class="clear" />
<h3><?php _e( 'Nuke WP e-Commerce', 'wpsc_st' ); ?></h3>
<p><?php _e( 'Select the WP e-Commerce tables you wish to empty then click Remove to permanently remove WP e-Commerce generated details from your WordPress database.', 'wpsc_st' ); ?></p>
<form method="post" onsubmit="showProgress()">
	<div id="poststuff">

		<div class="postbox" id="empty-wpecommerce-tables">
			<h3 class="hndle"><?php _e( 'Empty WP e-Commerce Tables', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove WP e-Commerce details.', 'wpsc_st' ); ?></p>
				<p><a href="javascript:void(0)" id="wpecommerce-checkall"><?php _e( 'Check All', 'jigo_st' ); ?></a> | <a href="javascript:void(0)" id="wpecommerce-uncheckall"><?php _e( 'Uncheck All', 'jigo_st' ); ?></a></p>
				<table class="form-table">

					<tr>
						<th>
							<label for="products"><?php _e( 'Products', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="products" name="wpsc_st_products"<?php echo disabled( $products, 0 ); ?> /> (<?php echo $products; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_variations"><?php _e( 'Product Variations', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_variations" name="wpsc_st_product_variations"<?php echo disabled( $variations, 0 ); ?> /> (<?php echo $variations; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_images"><?php _e( 'Product Images', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_images" name="wpsc_st_product_images"<?php echo disabled( $images, 0 ); ?> /> (<?php echo $images; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_files"><?php _e( 'Product Files', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_files" name="wpsc_st_product_files"<?php echo disabled( $files, 0 ); ?> /> (<?php echo $files; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_tags"><?php _e( 'Product Tags', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_tags" name="wpsc_st_product_tags"<?php echo disabled( $tags, 0 ); ?> /> (<?php echo $tags; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_categories"><?php _e( 'Product Categories', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_categories" name="wpsc_st_product_categories"<?php echo disabled( $categories, 0 ); ?> /> (<?php echo $categories; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="sales_orders"><?php _e( 'Sales', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="sales_orders" name="wpsc_st_sales_orders"<?php echo disabled( $orders, 0 ); ?> /> (<?php echo $orders; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="coupons"><?php _e( 'Coupons', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="coupons" name="wpsc_st_coupons"<?php echo disabled( $coupons, 0 ); ?> /> (<?php echo $coupons; ?>)
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

		<div class="postbox">
			<h3 class="hndle" id="empty-3rdparty-plugins"><?php _e( 'Empty 3rd Party Plugins', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove details created by other WP e-Commerce Plugins.', 'wpsc_st' ); ?></p>
				<table class="form-table">

<?php if( isset( $wishlist ) ) { ?>
					<tr>
						<th>
							<label for="wishlist"><?php _e( 'Wishlist', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="wishlist" name="wpsc_st_wishlist"<?php echo disabled( $wishlist, 0 ); ?> /> (<?php echo $wishlist; ?>)
							<span class="description"><?php echo sprintf( __( 'via %s', 'wpsc_st' ), '<a href="http://www.visser.com.au/wp-ecommerce/plugins/add-to-wishlist/" target="_blank">' . __( 'Add to Wishlist', 'wpsc_st' ) . '</a>' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( isset( $enquiries ) ) { ?>
					<tr>
						<th>
							<label for="enquiries"><?php _e( 'Enquiries', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="enquiries" name="wpsc_st_enquiries"<?php echo disabled( $enquiries, 0 ); ?> /> (<?php echo $enquiries; ?>)
							<span class="description"><?php echo sprintf( __( 'via %s', 'wpsc_st' ), '<a href="http://www.visser.com.au/wp-ecommerce/plugins/product-enquiry/" target="_blank">' . __( 'Product Enquiry', 'wpsc_st' ) . '</a>' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( isset( $credit_cards ) ) { ?>
					<tr>
						<th>
							<label for="creditcards"><?php _e( 'Credit Cards', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="creditcards" name="wpsc_st_creditcards"<?php echo disabled( $credit_cards, 0 ); ?> /> (<?php echo $credit_cards; ?>)
							<span class="description"><?php echo sprintf( __( 'via %s', 'wpsc_st' ), '<a href="http://www.visser.com.au/wp-ecommerce/plugins/offline-credit-card-processing/" target="_blank">' . __( 'Offline Credit Card Processing', 'wpsc_st' ) . '</a>' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( isset( $attributes ) ) { ?>
					<tr>
						<th>
							<label for="customfields"><?php _e( 'Attributes', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="customfields" name="wpsc_st_customfields"<?php echo disabled( $attributes, 0 ); ?> /> (<?php echo $attributes; ?>)
							<span class="description"><?php echo sprintf( __( 'via %s', 'wpsc_st' ), '<a href="http://www.visser.com.au/wp-ecommerce/plugins/custom-fields/" target="_blank">' . __( 'Custom Fields', 'wpsc_st' ) . '</a>' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( isset( $preview_files ) ) { ?>
					<tr>
						<th>
							<label for="previewfiles"><?php _e( 'Preview Files', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="previewfiles" name="wpsc_st_previewfiles"<?php echo disabled( $preview_files, 0 ); ?> /> (<?php echo $preview_files; ?>)
							<span class="description"><?php echo sprintf( __( 'via %s', 'wpsc_st' ), '<a href="http://getshopped.org/premium-upgrades/premium-plugin/jplayer-mp3-player/" target="_blank">' . __( 'WP e-Commerce Music Player', 'wpsc_st' ) . '</a>' ); ?></span>
						</td>
					</tr>

<?php } ?>
				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

		<div class="postbox">
			<h3 class="hndle" id="empty-product-by-category"><?php _e( 'Empty Products by Product Category', 'wpsc_st' ); ?></h3>
			<div class="inside">
<?php if( $categories ) { ?>
				<p><?php _e( 'Remove Products from specific Product Categories by selecting the Product Categories below, then click Remove to permanently remove those Products.', 'wpsc_st' ); ?></p>
				<ul>
	<?php foreach( $categories_data as $category_single ) { ?>
					<li>
						<label>
							<input type="checkbox" name="wpsc_st_categories[<?php echo $category_single->term_id; ?>]" value="<?php echo $category_single->term_id; ?>"<?php if( $category_single->count == 0 ) { ?> disabled="disabled"<?php } ?> />
							<?php echo $category_single->name; ?> (<?php echo $category_single->count; ?>)
						</label>
					</li>
	<?php } ?>
				</ul>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
<?php } else { ?>
				<p><?php _e( 'No Categories have been created.', 'wpsc_st' ); ?></p>
<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

		<div class="postbox" id="empty-wordpress-tables">
			<h3 class="hndle"><?php _e( 'Empty WordPress Tables', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove WordPress details.', 'wpsc_st' ); ?></p>
				<p><a href="javascript:void(0)" id="wordpress-checkall"><?php _e( 'Check All', 'jigo_st' ); ?></a> | <a href="javascript:void(0)" id="wordpress-uncheckall"><?php _e( 'Uncheck All', 'jigo_st' ); ?></a></p>
				<table class="form-table">

					<tr>
						<th>
							<label for="posts"><?php _e( 'Posts', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="posts" name="wpsc_st_posts"<?php echo disabled( $posts, 0 ); ?> /> (<?php echo $posts; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="post_categories"><?php _e( 'Post Categories', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="post_categories" name="wpsc_st_post_categories"<?php echo disabled( $post_categories, 0 ); ?> /> (<?php echo $post_categories; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="post_tags"><?php _e( 'Post Tags', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="post_tags" name="wpsc_st_post_tags"<?php echo disabled( $post_tags, 0 ); ?> /> (<?php echo $post_tags; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="links"><?php _e( 'Links', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="links" name="wpsc_st_links"<?php echo disabled( $links, 0 ); ?> /> (<?php echo $links; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="comments"><?php _e( 'Comments', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="links" name="wpsc_st_comments"<?php echo disabled( $comments, 0 ); ?> /> (<?php echo $comments; ?>)
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->

	<input type="hidden" name="action" value="nuke" />
</form>