<div class="postbox">
	<div class="inside" style="margin:1em;">
		<table id="uninstall-controls" style="width:100%;">
			<tr>
				<td>
					<label style="font-size:12px;"><input type="checkbox" id="toggle_log" name="log" class="checkbox" value="0" /> <?php _e( 'Show installation messages', 'wpsc_pd' ); ?></label>
				</td>
			</tr>
			<tr>
				<td id="toggle_uninstall">
					<textarea id="uninstall_log" rows="30" readonly="readonly" tabindex="2"><?php echo wpsc_st_return_uninstall_log( $uninstall->log ); ?></textarea>
				</td>
			</tr>
		</table>
	</div>
</div>
<!-- .postbox -->