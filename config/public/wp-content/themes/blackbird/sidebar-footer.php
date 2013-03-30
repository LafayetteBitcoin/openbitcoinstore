<div class="grid_12 alpha">
    <div class="widget_inner">
        <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
            <?php dynamic_sidebar('first-footer-widget-area'); ?>
        <?php else : ?>  
            <h3><?php _e('ABOUT YOUR BUSINESS','black-bird'); ?></h3>
            <p><?php _e('Premium WordPress Themes with Single Click Installation, Just a Click and your website is ready for use. Your Site is faster to built, easy to use & Search Engine Optimized.','black-bird'); ?></p>
            <br/>
            <p><?php _e('If your looking for help with our themes this is the place to be. We have a ton of great videos that show you how to setup our themes. And every theme comes with PDF & Video Documentation to help with almost any issue.','black-bird'); ?></p>               
        <?php endif; ?> 
    </div>
</div>
<div class="grid_6">
    <div class="widget_inner last">
        <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
            <?php dynamic_sidebar('second-footer-widget-area'); ?>
        <?php else : ?> 
            <h3><?php _e('SOCIAL LINK','black-bird'); ?></h3>
            <ul class="Social-links">
                <?php if (blackbird_get_option('blackbird_facebook') != '') { ?>
                    <li><a href="<?php echo blackbird_get_option('blackbird_facebook'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/facebook.png" /><?php _e('&nbsp;&nbsp;Join Us On Facebook','black-bird'); ?></a> </li>
                <?php } ?>  
                <?php if (blackbird_get_option('blackbird_twitter') != '') { ?>
                    <li><a href="<?php echo blackbird_get_option('blackbird_twitter'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" /><?php _e('&nbsp;&nbsp;Join Us On Twitter','black-bird'); ?></a></li>
                <?php } ?>
                <?php if (blackbird_get_option('blackbird_linked') != '') { ?>
                    <li><a href="<?php echo blackbird_get_option('blackbird_linked'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/linkdin.png" /><?php _e('&nbsp;&nbsp;Join Us On In.com','black-bird'); ?></a></li>
                <?php } ?>
                <?php if (blackbird_get_option('blackbird_rss') != '') { ?>
                    <li><a href="<?php echo blackbird_get_option('blackbird_rss'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/subscribe.png" /><?php _e('&nbsp;&nbsp;Subscribe to Our Blog','black-bird'); ?></a></li>
                <?php } ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6 omega">
    <div class="widget_inner">
        <?php if (is_active_sidebar('third-footer-widget-area')) : ?>
            <?php dynamic_sidebar('third-footer-widget-area'); ?>
        <?php else : ?> 
            <h3><?php _e('FOOTER SETUP','black-bird'); ?></h3>
            <p><?php _e('Footer is widgetized. To setup the footer, drag the required Widgets in Appearance -> Widgets Tab in the First, Second or Third Footer Widget Areas.','black-bird'); ?></P>
        <?php endif; ?>
    </div>
</div>
