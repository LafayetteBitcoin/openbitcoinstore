<h3><?php _e( 'Demo Mode', 'wpsc_st' ); ?></h3>
<p><?php _e( 'Show a banner at the top of every page stating this shop is currently in testing mode.', 'wpsc_st' ); ?></p>
<form method="post">

	<div id="poststuff">

		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Demo Store', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<table class="form-table">

					<tr>
						<td>
							<label><input type="checkbox" id="demo_store" name="options[demo_store]"<?php checked( $options['demo_store'], 1 ); ?>/> <?php _e( 'Enable Demo Store', 'wpsc_st' ); ?></label>
						</td>
					</tr>

					<tr>
						<td>
							<label for="demo_store_text"><?php _e( 'Message', 'wpsc_st' ); ?></label><br />
							<input type="text" id="demo_store_text" name="options[demo_store_text]" value="<?php echo $options['demo_store_text']; ?>" size="80" />
							<p class="description"><?php _e( 'Customise the message that appears at the top of every page.', 'wpsc_st' ); ?></p>
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
	<input type="hidden" name="action" value="demo" />

</form>