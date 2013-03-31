<?php
/**
 * This file is part of TheCartPress.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The loop that displays products in configurable GRID mode.
 *
 * @package TheCartPRess
 * @subpackage 
 * @since 1.1.3
 */
?>
<?php /* Display navigation to next/previous pages when applicable */ ?>

<?php /* If there are no products to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
      <article id="post-0" class="post no-results not-found">
          <header class="entry-header">
              <h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
          </header><!-- .entry-header -->

          <div class="entry-content">
              <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?>
            </p>
              <?php get_search_form(); ?>
          </div><!-- .entry-content -->
      </article><!-- #post-0 -->
<?php endif; ?>

<?php
if ( ! isset( $instance ) ) $instance = get_option( 'ttc_settings' );
$suffix = '-' . get_post_type( get_the_ID() );
if ( ! isset( $instance['title_tag' . $suffix] ) ) $suffix = '';

$see_title				= isset( $instance['see_title' . $suffix] ) ? $instance['see_title' . $suffix] : true;
$title_tag				= isset( $instance['title_tag' . $suffix] ) ? $instance['title_tag' . $suffix] : '';
$see_image				= isset( $instance['see_image' . $suffix] ) ? $instance['see_image' . $suffix] : true;
$image_size				= isset( $instance['image_size' . $suffix] ) ? $instance['image_size' . $suffix] : 'thumbnail';
$see_excerpt			= isset( $instance['see_excerpt' . $suffix] ) ? $instance['see_excerpt' . $suffix] : true;
$see_content			= isset( $instance['see_content' . $suffix] ) ? $instance['see_content' . $suffix] : false;
$see_price				= isset( $instance['see_price' . $suffix] ) ? $instance['see_price' . $suffix] : true;
$see_buy_button			= isset( $instance['see_buy_button' . $suffix] ) ? $instance['see_buy_button' . $suffix] : false;
$see_author				= isset( $instance['see_author' . $suffix] ) ? $instance['see_author' . $suffix] : false;
$see_posted_on			= isset( $instance['see_posted_on' . $suffix] ) ? $instance['see_posted_on' . $suffix] : false;
$see_taxonomies			= isset( $instance['see_taxonomies' . $suffix] ) ? $instance['see_taxonomies' . $suffix] : false;
$see_meta_utilities		= isset( $instance['see_meta_utilities' . $suffix] ) ? $instance['see_meta_utilities' . $suffix] : false;
$see_sorting_panel		= isset( $instance['see_sorting_panel' . $suffix] ) ? $instance['see_sorting_panel' . $suffix] : false;
$number_of_columns		= isset( $instance['columns' . $suffix] ) ? (int)$instance['columns' . $suffix] : 2;
//custom areas. Usefull to insert other template tag from WordPress or anothers plugins 
$see_first_custom_area	= isset( $instance['see_first_custom_area' . $suffix] ) ? $instance['see_first_custom_area' . $suffix] : false;
$see_second_custom_area	= isset( $instance['see_second_custom_area' . $suffix] ) ? $instance['see_second_custom_area' . $suffix] : false;
$see_third_custom_area	= isset( $instance['see_third_custom_area' . $suffix] ) ? $instance['see_third_custom_area' . $suffix] : false;
$see_pagination			= isset( $instance['see_pagination' . $suffix] ) ? $instance['see_pagination' . $suffix] : false;
$column = $number_of_columns;

if ( isset( $instance['title_tag' . $suffix] ) && $instance['title_tag' . $suffix] != '' ) {
	$title_tag = '<' . $instance['title_tag' . $suffix] . ' class="entry-title">';
	$title_end_tag = '</' . $instance['title_tag' . $suffix] . '>';
} else {
	$title_tag = '';
	$title_end_tag = '';
}
?>

<?php if ( $see_sorting_panel ) {
	tcp_the_sort_panel();
} ?>

<?php /* Start the Loop.*/ ?>

<table class="tcp_products_list">
<tr class="tcp_first-row">
<?php 
while ( have_posts() ) : the_post();
	if ( $column == 0 ) : $column = $number_of_columns ?>
	</tr><tr>
	<?php endif;
	$tcp_col = $number_of_columns - $column + 1;
	$class = array( 'tcp_' . $number_of_columns . '_cols', 'tcp_col_' . $tcp_col );
	//$td_class = 'class="' . join( ' ', get_post_class( $class ) ) . '"'; ?>
	<td id="td-post-<?php the_ID(); ?>" class="tcp_col <?php echo implode( ' ', $class ); ?>">
	<?php $column--;?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if ( $see_posted_on ) : ?>

			<div class="entry-meta">

				<?php tcp_posted_on(); ?> <?php tcp_posted_by(); ?>

			</div><!-- .entry-meta -->

			<?php endif; ?>

			<?php if ( $see_title ) : ?>
				<?php echo $title_tag;?><a href="<?php the_permalink( );?>"><?php the_title(); ?></a><?php echo $title_end_tag;?>
			<?php endif; ?>

            <div class="wrapper-price">
			  <?php if ( $see_price ) :?>
              <div class="entry-price">
                <?php tcp_the_price_label();?>
              </div><!-- entry-price -->
              <?php endif;?>

			<?php if ( $see_image ) : ?>

				<div class="entry-post-thumbnail">

					<!-- <a class="tcp_size-<?php echo $image_size; ?>" href="<?php the_permalink(); ?>"><?php if ( function_exists( 'the_post_thumbnail' ) ) the_post_thumbnail( $image_size ); ?></a>-->

					<?php $image = '<a class="tcp_size-' . $image_size . '" href="' . get_permalink() . '">';
					$image .= tcp_get_the_thumbnail( get_the_ID(), 0, 0, $image_size ) . '</a>';
					$image = apply_filters( 'tcp_get_image_in_excerpt', $image, get_the_ID() );
					echo $image; ?>

				</div><!-- .entry-post-thumbnail -->

			<?php endif; ?>

			</div><!-- .wrapper-price -->

			<?php if ( $see_excerpt ) : ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php endif; ?>

			<?php if ( $see_buy_button ) :?>
			<div class="entry-buy-button">	
				<?php tcp_the_buy_button();?>
			</div>
			<?php endif;?>

			<?php if ( $see_content ) : ?>
			<div class="entry-content">
				<?php the_content( __( 'More <span class="meta-nav">&rarr;</span>', 'tcp' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'tcp' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->
			<?php endif; ?>

			<?php if ( $see_first_custom_area ) : ?>
		    <?php endif;?>
		    <?php if ( $see_second_custom_area ) : ?>
		    <?php endif;?>
		    <?php if ( $see_third_custom_area ) : ?>
		    <?php endif;?>

			<?php if ( $see_author ) :?>
				<?php if ( get_the_author_meta( 'description') ) : // If a user has filled out their description, show a bio on their products  ?>
				<div id="entry-author-info">
					<div id="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'tcp_author_bio_avatar_size', 60 ) ); ?>
					</div><!-- #author-avatar -->
					<div id="author-description">
						<h2><?php printf( esc_attr__( 'About %s', 'tcp' ), get_the_author_meta() ); ?></h2>
						<?php the_author_meta( 'description'); ?>
						<div id="author-link">
						</div><!-- #author-link	-->
					</div><!-- #author-description -->
				</div><!-- #entry-author-info -->
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $see_taxonomies ) : ?>
				<div class="entry-taxonomies">
					<span class="tcp_taxonomies">
					<?php
					$taxonomies = get_object_taxonomies( get_post_type(), 'objects' );
					foreach( $taxonomies as $id => $taxonomy ) :
						$terms_list = get_the_term_list( 0, $id, '', ', ' );
						if ( strlen( $terms_list ) > 0 ) : ?>
						<span class="tcp_taxonomy tcp_taxonomy_<?php echo $taxonomy->name;?>"><?php echo $taxonomy->labels->singular_name; ?>:
						<?php echo $terms_list;?>
						</span>
						<?php endif; 
					endforeach;?>
					</span>
				</div><!-- taxonomies -->
			<?php endif;?>

			<?php if ( $see_meta_utilities ) : ?>
				<div class="entry-utilities">

				<?php if ( comments_open() ) : ?>
                <?php if ( isset( $show_sep) && $show_sep ) : ?>
                <span class="sep"> | </span>
                <?php endif; // End if $show_sep ?>
                <span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a review', 'ecommerce-twentyeleven' ) . '</span>', __( '<b>1</b> Review', 'twentyeleven' ), __( '<b>%</b> Reviews', 'ecommerce-twentyeleven' ) ); ?></span>
                <?php endif; // End if comments_open() ?>
    
                <?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>

				</div><!-- .entry-utility -->
			<?php endif; ?>
		</div><!-- #post-## -->
</td>
<?php endwhile; // End the loop ?>
<?php for(; $column > 0; $column-- ) : 
	$class = array( 'tcp_' . $number_of_columns . '_cols', 'tcp_col_' . ++$tcp_col );?>
	<td class="tcp_col <?php echo implode( ' ', $class ); ?> tcp_td_empty">&nbsp;</td>
<?php endfor; ?>
</tr></table>

<?php /* Display pagination */
if ( $see_pagination ) tcp_get_the_pagination(); ?>

