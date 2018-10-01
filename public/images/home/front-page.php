<?php get_header(); ?>

<?php get_template_part( 'section', 'slider' ); ?>


<!-- Content
============================================= -->
<section id="content">

	<div class="content-wrap">

		<div class="container clearfix">

			<div class="modal-on-load" data-target="#myModal1" data-delay="5000" data-timeout="7000"></div>

			<?php get_template_part( 'section', 'popup' ); ?>

			<?php get_template_part( 'section', 'about' ); ?>

			<?php get_template_part( 'section', 'services' ); ?>

			<?php
			// get_template_part( 'section', 'technology' );
			?>

			<?php get_template_part( 'section', 'work' ); ?>

			<?php get_template_part( 'section', 'team' ); ?>

			<?php 
			get_template_part( 'section', 'portfolio' );
			?>

			<?php get_template_part( 'section', 'client' ); ?>

		</div>

	<?php get_template_part( 'section', 'testimonial' ); ?>

		<div class="container clearfix">
			<?php get_template_part( 'section', 'contact' ); ?>
		</div>
	
	</div>

</section><!-- #content end -->

<?php get_footer(); ?>