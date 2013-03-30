<?php
/**
 * The template for displaying front page pages.
 *
 */
?>
<?php get_header(); ?>  
<div class="clear"></div>
<!--Start Slider Wrapper-->
<div class="slider-wrapper">            
    <div class="flexslider">
        <ul class="slides">
            <!--Start Slider-->
            <?php
            //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider
            $mystring1 = blackbird_get_option('blackbird_slideimage1');
            $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
            $check_img_ofset = 0;
            foreach ($value_img as $get_value) {
                if (preg_match("/$get_value/", $mystring1)) {
                    $check_img_ofset = 1;
                }
            }
            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
            ?>
            <?php if ($check_img_ofset == 0 && blackbird_get_option('blackbird_slideimage1') != '') { ?>
                <li><?php echo blackbird_get_option('blackbird_slideimage1'); ?></li>
            <?php } else { ?>  
                <li>  <?php if (blackbird_get_option('blackbird_slideimage1') != '') { ?>
                        <img  src="<?php echo blackbird_get_option('blackbird_slideimage1'); ?>" alt=""/>
                    <?php } else { ?>
                        <img  src="<?php echo get_template_directory_uri(); ?>/images/slider1.png" alt=""/>
                    <?php } ?>
                    <div class="flex-caption">
                        <?php if (blackbird_get_option('blackbird_sliderheading1') != '') { ?>

                            <h1><a href="<?php
                    if (blackbird_get_option('blackbird_Sliderlink1') != '') {
                        echo blackbird_get_option('blackbird_Sliderlink1');
                    }
                            ?>"><?php echo stripslashes(blackbird_get_option('blackbird_sliderheading1')); ?></a></h1>
                        <?php } else { ?>
                            <h1><a href="#"><?php _e('Elegancy with Simplicity', 'black-bird'); ?></a></h1>
                            <?php } ?>
                            <?php if (blackbird_get_option('blackbird_sliderdes1') != '') { ?>
                            <p>					   
                            <?php echo stripslashes(blackbird_get_option('blackbird_sliderdes1')); ?>
                            </p>
                        <?php } else { ?>
                            <p><?php _e('Blackbird Theme allows you to create your website through an easy to use themes options panel.', 'black-bird'); ?></p>
                <?php } ?>						 
                    </div>
                </li>
<?php } ?>
            <!--End Slider-->
        </ul>
    </div>
</div>
<!--End Silder Wrapper-->
<div class="clear"></div>
<div class="seprator"></div>
<div class="content">
    <?php if (blackbird_get_option('blackbird_mainheading') != '') { ?>
        <h1><?php echo stripslashes(blackbird_get_option('blackbird_mainheading')); ?></h1>
    <?php } else { ?>
        <h1><?php _e('Create beautiful &amp; Professional Website with the Blackbird Theme easily &amp; quickly.', 'black-bird'); ?></h1>
<?php } ?>
</div>
<div class="feature-content">
    <div class="circle-content">
        <div class="grid_8 alpha">
            <div class="feature-content-inner one">
                <?php if (blackbird_get_option('blackbird_wimg1') != '') { ?>
                    <div class="circle"><img src="<?php echo blackbird_get_option('blackbird_wimg1'); ?>" /></div>
                <?php } else { ?>
                    <div class="circle"><img src="<?php echo get_template_directory_uri(); ?>/images/img1.png" /></div>
                        <?php } ?>
                        <?php if (blackbird_get_option('blackbird_headline1') != '') { ?><h1><a href="<?php
                        if (blackbird_get_option('blackbird_link1') != '') {
                            echo blackbird_get_option('blackbird_link1');
                        }
                            ?>"><?php echo stripslashes(blackbird_get_option('blackbird_headline1')); ?></a></h1>
                <?php } else { ?> <h1><a href="#"><?php _e('Unique Circular Design', 'black-bird'); ?></a></h1>
                <?php } ?>
                <?php if (blackbird_get_option('blackbird_feature1') != '') { ?>
                    <p><?php echo stripslashes(blackbird_get_option('blackbird_feature1')); ?></p>
                <?php } else { ?>
                    <p><?php _e('BlackBird Wordpress Theme comes with a Unique Circular Pattern, which makes your website stand out from the crowd.', 'black-bird'); ?></p>
<?php } ?>
                <a class="read-more" href="<?php
if (blackbird_get_option('blackbird_link1') != '') {
    echo blackbird_get_option('blackbird_link1');
}
?>">Read More</a> </div>
        </div>
        <div class="grid_8">
            <div class="feature-content-inner two">
                        <?php if (blackbird_get_option('blackbird_fimg2') != '') { ?>
                    <div class="circle"><img src="<?php echo blackbird_get_option('blackbird_fimg2'); ?>" /></div>
                <?php } else { ?>
                    <div class="circle"><img src="<?php echo get_template_directory_uri(); ?>/images/img2.png" /></div>
                <?php } ?>
                <?php if (blackbird_get_option('blackbird_headline2') != '') { ?><h1><a href="<?php
                    if (blackbird_get_option('blackbird_link2') != '') {
                        echo blackbird_get_option('blackbird_link2');
                    }
                    ?>"><?php echo stripslashes(blackbird_get_option('blackbird_headline2')); ?></a></h1>
                <?php } else { ?> <h1><a href="#"><?php _e('Professional Design', 'black-bird'); ?></a></h1>
                   <?php } ?>
<?php if (blackbird_get_option('blackbird_feature2') != '') { ?>
                    <p><?php echo stripslashes(blackbird_get_option('blackbird_feature2')); ?></p>
                <?php } else { ?>
                    <p><?php _e('The BlackBird Wordpress Theme is highly optimized to look Professional. So that your website looks great to your visitors.', 'black-bird'); ?></p>
                <?php } ?>
                <a class="read-more" href="<?php
                if (blackbird_get_option('blackbird_link2') != '') {
                    echo blackbird_get_option('blackbird_link2');
                }
                ?>"><?php _e('Read More', 'black-bird') ?></a> </div>
        </div>
        <div class=" grid_8 omega">
            <div class="feature-content-inner three">
                <?php if (blackbird_get_option('blackbird_fimg3') != '') { ?>
                    <div class="circle"><img src="<?php echo blackbird_get_option('blackbird_fimg3'); ?>" /></div>
                <?php } else { ?>
                    <div class="circle"><img src="<?php echo get_template_directory_uri(); ?>/images/img3.png" /></div>
                <?php } ?>
                <?php if (blackbird_get_option('blackbird_headline3') != '') { ?><h1><a href="<?php
                    if (blackbird_get_option('blackbird_link3') != '') {
                        echo blackbird_get_option('blackbird_link3');
                    }
                    ?>"><?php echo stripslashes(blackbird_get_option('blackbird_headline3')); ?></a></h1>
                <?php } else { ?> <h1><a href="#"><?php _e('Easily build Website', 'black-bird'); ?></a></h1>
                <?php } ?>
                <?php if (blackbird_get_option('blackbird_feature3') != '') { ?>
                                    <p><?php echo stripslashes(blackbird_get_option('blackbird_feature3')); ?></p>
                <?php } else { ?>
                                    <p><?php _e('This BlackBird Wordpress Theme gives you the easiness of building your site without any coding skills required.', 'black-bird'); ?></p>
                <?php } ?>
                <a class="read-more" href="<?php
if (blackbird_get_option('blackbird_link3') != '') {
    echo blackbird_get_option('blackbird_link3');
}
?>"><?php _e('Read More', 'black-bird'); ?></a> </div>
        </div>
    </div>
</div>			
<div class="clear"></div>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>