<?php
	$testimonial = new WP_Query(
		array(
			'post_type' => 'mv-testimonials',
			'posts_per_page' => $number,
			'post_status' => 'publish'
		)
	);

	if ( $testimonial->have_posts() ) {
		while( $testimonial->have_posts() ) {
			$testimonial->the_post();

            $url_meta = get_post_meta( get_the_ID(), 'mv_testimonials_user_url', true );
			$occupation_meta = get_post_meta( get_the_ID(), 'mv_testimonials_occupation', true );
			$company_meta = get_post_meta( get_the_ID(), 'mv_testimonials_company', true );
			?>
			<div class="testimonial-item">
				<div class="title">
					<h3><?php the_title(); ?></h3>
				</div>

				<div class="content">
                    <?php
                    if ( $image ) {
                    ?>
                    <div class="thumb">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail( array( 70, 70 ) );
                        }?>
                    </div>
                    <?php
                    }
                    ?>
				</div>
                <?php the_content() ?>
				<div class="meta">
                    <?php if ( $occupation ): ?>
                        <span class="occupation"><?php echo esc_html( $occupation_meta ) ?></span>
                    <?php endif; ?>

					<?php if ( $company ): ?>
                        <span class="company"> <a href="<?php echo esc_attr( $url_meta ) ?>"><?php echo esc_attr( $url_meta ) ?></a> <?php echo esc_html( $company_meta ) ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
		wp_reset_postdata();
	}
?>


