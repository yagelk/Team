<?php

/**
 * Template Name: Single page member
 * Author: Yagel Kahalani
 * Email Author: yagelk18@gmail.com
 * URL Author: http://www.yagelk.com
 * Template Post Type: post,page,member
 */
 ?>
<?php get_header(); ?>
    <?php
        $prefix = '_yk_'; 
        global $post;
        $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $phone = esc_html( get_post_meta( $post->ID,'_yk_phone', true ) );
        $role = esc_html( get_post_meta( $post->ID, '_yk_role', true ) );
    ?>
    <div class="row">
    <?php while ( have_posts() ) : the_post(); ?>
        <div id=post-"<?php the_ID(); ?>">
            <img style="height: 250px; width: 100%;" class="img img-responsive" src="<?php echo $large_image_url[0]; ?>"  id="img_profile_bg" />
                <div id="container prop-member" style="width: 70%; margin: 0 auto;">
                    <div class="item-content clearfix" style="text-align: center;">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="about" ><?php the_content(); ?>
                    </div>
                        
                    <p style="float: right;" id="role_member"><strong><?php echo $role ?></strong></p>
                    <p style="float: left;" id="phone_member"><a href="tel:<?php echo $phone ?>"><?php echo $phone ?></a></p>
                    <!-- <div class="clearfix visible-xs-block"></div> -->
                </div>
        </div>
    <?php endwhile; ?>
    </div>
<?php get_footer(); ?>