<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Amazon S3 Uploads</h2>
	<?php if (isset($_GET['msg']) && !empty($_GET['msg'])): ?>
	<div id="asssu-settings_updated" class="updated settings-error"> 
		<p><strong><?php echo $_GET['msg']; ?></strong></p>
	</div>
	<?php endif; ?>
	<form method="post" action="">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="access_key"><?php _e('Plugin Status'); ?></label></th>
				<td>
				<?php if ($this->enabled): ?>
					<strong><?php _e('Active'); ?></strong>
				<?php else: ?>
					<font color="#ff0000"><?php _e('Disabled'); ?></font>
				<?php endif; ?>
				</td>
			</tr>
			<?php if ($c->mode === 'optional'): ?>
			<tr valign="top">
				<td colspan="2">
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Use predefined settings'); ?></span></legend>
						<label for="use_predefined">
							<input name="use_predefined" type="checkbox" id="use_predefined" value="1" <?=($c->use_predefined ? 'checked="checked"' : '')?> />
							<?php _e('Use Amazon S3 configuration defined by your network administrator.'); ?>
						</label>
					</fieldset>
				</td>
			</tr>
			<?php endif; ?>
			<?php if ($c->mode !== 'optional' || !$c->use_predefined): ?>
			<tr valign="top">
				<th scope="row"><label for="access_key"><?php _e('Amazon Access Key ID'); ?></label></th>
				<td>
					<input name="access_key" type="text" id="access_key" value="<?=$c->access_key?>" class="regular-text" />
					<span class="description"><a href="https://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&action=access-key" target="_blanck"><?php _e('Amazon S3 Access'); ?></a></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="secret_key"><?php _e('Amazon Secret Access Key'); ?></label></th>
				<td><span id="span_sercret_key"><?=$c->getSafeSecretKey()?><span class="description"><a href="#" onClick="jQuery('#span_sercret_key').hide();jQuery('#secret_key').attr('value', '');jQuery('#secret_key').show();">Change</a></span></span><input name="secret_key" type="text" id="secret_key" value="not_used" class="regular-text" style="display:none;" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="bucket_name"><?php _e('Amazon Bucket Name'); ?></label></th>
				<td>
					<input name="bucket_name" type="text" id="bucket_name" value="<?=$c->bucket_name?>" class="regular-text" />
					<span class="description"><?php _e('This plugin will not create a bucket if it is not there. Please make sure you have already created one with proper ACL permissions.'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="bucket_subdir"><?php _e('Amazon Bucket Subdirectory'); ?></label></th>
				<td>
					<input name="bucket_subdir" type="text" id="bucket_subdir" value="<?=$c->bucket_subdir?>" class="regular-text" />
					<span class="description"><?php _e('If you want to store all images in a bucket\'s subdirectory, like \'media/blog\'.'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="exclude"><?php _e('Exclude File Extensions'); ?></label></th>
				<td>
					<input name="exclude" type="text" id="exclude" value="<?=$c->exclude?>" class="regular-text" />
					<span class="description"><?php _e('If you want to exclude some filetypes from being uploaded to Amazon S3, like \'.js, .php, .doc, .mkv\'.'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Use SSL'); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Use SSL'); ?></span></legend>
						<label for="use_ssl">
							<input name="use_ssl" type="checkbox" id="use_ssl" value="1" <?=($c->use_ssl ? 'checked="checked"' : '')?> />
							<?php _e('Use SSL connection for S3 transfers'); ?>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="cron_interval"><?php _e('Cron Execution Interval'); ?></label></th>
				<td>
					<select name="cron_interval" id="cron_interval">
						<?php
							$selected_value = intval($c->cron_interval);
							for ($counter = 300; $counter <= 1800; $counter = $counter + 300):
							    $selected = $selected_value === $counter ? ' selected="selected"' : '';
								print "\n\t".'<option value="'.$counter.'"'.$selected.'>'.$counter.' seconds</option>';
							endfor;
					 	?>
					</select>
					<span class="description"><?php _e('This plugin uses wordpress cron to schedule and upload the files to Amazon S3. Please make sure that yourblog.com/wp-cron.php is setup properly.'); ?></span>
				</td>
			</tr>	
			<tr valign="top">
				<th scope="row"><label for="cron_limit"><?php _e('Upload Limit'); ?></label></th>
				<td>
					<select name="cron_limit" id="cron_limit">
						<?php
							$selected_value = $c->cron_limit;
							for ($counter = 10; $counter <= 100; $counter = $counter + 10):
							    $selected = $selected_value === $counter ? ' selected="selected"' : '';
								print "\n\t".'<option value="'.$counter.'"'.$selected.'>'.$counter.'</option>';
							endfor;
					 	?>
					</select>
					<span class="description"><?php _e('No of files to upload on each cron execution.'); ?></span>
				</td>
			</tr>	
			<?php endif; ?>
		</table>
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
	</form>
	<span class="description">If you find this plugin usefull, please <a href="#" onclick="jQuery('#asssu-donate').show();">donate</a>.</span>
	<div id="asssu-donate" style="display:none;">
		<span class="description">
		No minimum donation amount, it's totally up to you.<br />
		If you prefer to send me a handicraft, then <a href="mailto:atvdev@gmail.com">ask for my address</a>.<br />
		Cheers & beers!
		</span>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="7T88Q3EHGD9RS">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
</div>
