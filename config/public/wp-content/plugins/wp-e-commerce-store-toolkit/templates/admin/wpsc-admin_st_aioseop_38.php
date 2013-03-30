<p class="description"><?php _e( 'Use this form to enter All in One SEO Pack details for this Product.', 'wpsc_st' ); ?></p>
<table>

	<tr>
		<td scope="row" style="padding:0 0 0.5em 0;">
			<label for="wpsc_st_aioseop_title"><?php _e( 'Title', 'wpsc_st' ); ?>:</label>
		</td>
		<td>
			<input type="text" id="wpsc_st_aioseop_title" name="meta[_aioseop_title]" value="<?php echo $title; ?>" size="62" />
		</td>
	</tr>

	<tr>
		<td scope="row" style="padding:0 0 0.25em 0;">
			<label for="wpsc_st_aioseop_description"><?php _e( 'Description', 'wpsc_st' ); ?>:</label>
		</td>
		<td>
			<textarea id="wpsc_st_aioseop_description" name="meta[_aioseop_description]" rows="3" cols="60"><?php echo $description; ?></textarea>
		</td>
	</tr>

	<tr>
		<td scope="row" style="padding:0 0 0.25em 0;">
			<label for="wpsc_st_aioseop_keywords"><?php _e( 'Keywords (comma separated)', 'wpsc_st' ); ?>:</label>
		</td>
		<td>
			<input type="text" id="wpsc_st_aioseop_keywords" name="meta[_aioseop_keywords]" value="<?php echo $keywords; ?>" size="62" />
		</td>
	</tr>

	<tr>
		<td scope="row" style="padding:0 0 0.25em 0;">
			<label for="wpsc_st_aioseop_title_atr"><?php _e( 'Title atrributes', 'wpsc_st' ); ?>:</label>
		</td>
		<td>
			<input type="text" id="wpsc_st_aioseop_title_atr" name="meta[_aioseop_titleatr]" value="<?php echo $title_atr; ?>" size="62" />
		</td>
	</tr>

	<tr>
		<td scope="row" style="padding:0 0 0.25em 0;">
			<label for="wpsc_st_aioseop_menu_label"><?php _e( 'Menu label', 'wpsc_st' ); ?>:</label>
		</td>
		<td>
			<input type="text" id="wpsc_st_aioseop_menu_label" name="meta[_aioseop_menulabel]" value="<?php echo $menu_label; ?>" size="62" />
		</td>
	</tr>

</table>