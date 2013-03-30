<h3><?php _e( 'WP e-Commerce Tools', 'wpsc_st' ); ?></h3>
<p><?php _e( 'A growing set of commonly-used WP e-Commerce administration tools aimed at web developers and store maintainers.', 'wpsc_st' ); ?></p>
<form method="post">

	<div id="poststuff">

		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Tools', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<table class="form-table">

					<tr>
						<td>
							<a href="<?php echo add_query_arg( array( 'page' => 'wpsc_st-toolkit', 'action' => 'relink-pages' ) ); ?>"><?php _e( 'Re-link WP e-Commerce Pages', 'wpsc_st' ); ?></a>
							<p class="description"><?php _e( 'This tool will re-link the default WP e-Commerce Pages where customers are encountering dead shop links (e.g. Products Page, Checkout, Transaction Results, etc.)', 'wpsc_st' ); ?></p>
						</td>
					</tr>

					<tr>
						<td>
							<a href="<?php echo add_query_arg( array( 'page' => 'wpsc_st-toolkit', 'action' => 'relink-existing-preregistered-sales' ) ); ?>"><?php _e( 'Re-link existing Sales from pre-registered Users', 'wpsc_st' ); ?></a>
							<p class="description"><?php _e( 'This tool will attempt to re-link Sales with no User linked to existing Users, this is common where a customer makes a purchase then later registers for the site. Using this tool their Sale will appear within My Account.', 'wpsc_st' ); ?></p>
						</td>
					</tr>

					<tr>
						<td>
							<a href="<?php echo add_query_arg( array( 'page' => 'wpsc_st-toolkit', 'action' => 'fix-wpsc_version' ) ); ?>"><?php _e( 'Repair WordPress option \'wpsc_version\'', 'wpsc_st' ); ?></a>
							<p class="description"><?php _e( 'If you have upgraded to WP e-Commerce 3.8 then rolled back to 3.7 this will fix common store issues.', 'wpsc_st' ); ?></p>
						</td>
					</tr>

					<tr>
						<td>
							<a href="<?php echo add_query_arg( array( 'page' => 'wpsc_st-toolkit', 'action' => 'clear-claimed_stock' ) ); ?>"><?php _e( 'Empty the \'claimed_stock\' table', 'wpsc_st' ); ?></a>
							<p class="description"><?php _e( 'This tool will dump all pending stock that has not been purchased for (e.g. abandoned carts).', 'wpsc_st' ); ?></p>
						</td>
					</tr>

				</table>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->

	<input type="submit" value="<?php _e( 'Save Changes', 'wpsc_st' ); ?>" class="button-primary" />
	<input type="hidden" name="action" value="tools" />

</form>