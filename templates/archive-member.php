<?php
 /*Template Name: Team
 * Author: Yagel Kahalani
 * Email Author: yagelk18@gmail.com
 * URL Author: http://www.yagelk.com
 */
?>
    <?php get_header(); ?>
    <?php 
        $prefix = '_yk_'; 
        $args = array(
        'post_type' => 'team',
        'posts_per_page' => 0,
        );
        $members_query = new WP_Query( $args );
    ?>

    <!-- Start container -->
    <div class="container" role="main">
        <?php if($members_query->have_posts()) : ?>
        <div class="row">
            <?php                
                while($members_query->have_posts()) : $members_query->the_post();
                $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                global $post;
                $phone = esc_html( get_post_meta( $post->ID,'_yk_phone', true ) );
                $role = esc_html( get_post_meta( $post->ID, '_yk_role', true ) );
            ?>
            <div class="col-sm-6 col-md-4 col-xs-12" >
                <div id=post-"<?php the_ID(); ?>" style="border: 1px solid black; padding: 0px; margin: 10px;">
                    <img style="height: 250px; width: 100%;" class="img img-responsive" src="<?php echo $large_image_url[0]; ?>"  id="img_profile_bg" />
                        <div id="prop-member" style="padding: 20px;">
                            <div class="item-content clearfix">
                                <h2 style="text-align: center;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php the_content(); ?>
                            </div>
                            <div class="row">
                                <p style="float: right;" id="role_member"><strong><?php echo $role ?></strong></p>
                                <p style="float: left;" id="phone_member"><a href="tel:<?php echo $phone ?>"><?php echo $phone ?></a></p>
                            </div>
                            <!-- <div class="clearfix visible-xs-block"></div> -->
                        </div>
                </div>
                    
            </div>
            <?php
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>  
    </div>
    <!-- End Container -->

<?php get_footer(); ?>
