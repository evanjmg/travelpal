<?php

/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views
 * @since      1.0.0
 * @version    1.2.8
 */
?>

<!-- MOLONGUI AUTHORSHIP PLUGIN <?php echo MOLONGUI_AUTHORSHIP_VERSION ?> -->
<!-- <?php echo MOLONGUI_AUTHORSHIP_WEB ?> -->
<div class="molongui-table molongui-author-box-container
			mabc-shadow-<?php echo ( ( isset( $options['molongui_authorship_box_shadow'] ) and !empty( $options['molongui_authorship_box_shadow'] ) ) ? $options['molongui_authorship_box_shadow'] : 'left' );?>
			mabc-border-<?php echo ( ( isset( $options['molongui_authorship_box_border'] ) and !empty( $options['molongui_authorship_box_border'] ) ) ? $options['molongui_authorship_box_border'] : 'none' );?>"
     style="border-color: <?php echo $options['molongui_authorship_box_border_color']; ?>">


	<!-- Author thumbnail -->

	<?php if ( $author_img ) : ?>
	<div class="molongui-table-cell molongui-author-box-thumbnail">
		<?php if ( $author_link ) echo '<a href="' . esc_url( $author_link ) .'" target="_blank">'; ?>
			<?php echo $author_img; ?>
		<?php if ( $author_link ) echo '</a>'; ?>
	</div>
	<?php endif; ?>

	<!-- Author social -->

	<?php if ( ( isset( $options['molongui_authorship_icons_show'] ) and !empty( $options['molongui_authorship_icons_show'] ) and $options['molongui_authorship_icons_show'] == 1 ) and
	           ( $author_tw or $author_fb or $author_in or $author_gp or $author_yt or $author_pi or $author_tu or $author_ig or $author_xi or $author_re or $author_vk or $author_fl or
	             $author_vi or $author_me or $author_we or $author_de or $author_st or $author_my or $author_ye or $author_mi or $author_so or $author_la or $author_fo or $author_sp or
	             $author_vm )
	) :

		// Get social icon style
		$ico_style = $options['molongui_authorship_icons_style']; if ( $ico_style && $ico_style != 'default' ) $ico_style = '-' . $ico_style; elseif ( $ico_style == 'default' ) $ico_style = '';

		// Get social icon size
		$ico_size  = $options['molongui_authorship_icons_size']; if ( empty( $ico_size ) ) $ico_size = 'normal';

		// Get social icon color
		$ico_color = $options['molongui_authorship_icons_color']; if ( $ico_color ) $ico_color = 'color: ' . $ico_color . ';';
	?>
	<div class="molongui-table-cell molongui-author-box-social">
		<?php if ( $author_tw ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_tw ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-twitter"></i></a></div><?php endif; ?>
		<?php if ( $author_fb ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_fb ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-facebook"></i></a></div><?php endif; ?>
		<?php if ( $author_in ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_in ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-linkedin"></i></a></div><?php endif; ?>
		<?php if ( $author_gp ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_gp ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-gplus"></i></a></div><?php endif; ?>
		<?php if ( $author_yt ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_yt ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-youtube"></i></a></div><?php endif; ?>
		<?php if ( $author_pi ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_pi ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-pinterest"></i></a></div><?php endif; ?>
		<?php if ( $author_tu ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_tu ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-tumblr"></i></a></div><?php endif; ?>
		<?php if ( $author_ig ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_ig ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-instagram"></i></a></div><?php endif; ?>
		<?php if ( $author_xi ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_xi ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-xing"></i></a></div><?php endif; ?>
		<?php if ( $author_re ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_re ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-renren"></i></a></div><?php endif; ?>
		<?php if ( $author_vk ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_vk ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-vkontakte"></i></a></div><?php endif; ?>
		<?php if ( $author_fl ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_fl ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-flickr"></i></a></div><?php endif; ?>
		<?php if ( $author_vi ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_vi ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-vine"></i></a></div><?php endif; ?>
		<?php if ( $author_me ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_me ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-meetup"></i></a></div><?php endif; ?>
		<?php if ( $author_we ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_we ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-sina-weibo"></i></a></div><?php endif; ?>
		<?php if ( $author_de ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_de ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-deviantart"></i></a></div><?php endif; ?>
		<?php if ( $author_st ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_st ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-stumbleupon"></i></a></div><?php endif; ?>
		<?php if ( $author_my ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_my ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-myspace"></i></a></div><?php endif; ?>
		<?php if ( $author_ye ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_ye ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-yelp"></i></a></div><?php endif; ?>
		<?php if ( $author_mi ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_mi ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-mixi"></i></a></div><?php endif; ?>
		<?php if ( $author_so ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_so ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-soundcloud"></i></a></div><?php endif; ?>
		<?php if ( $author_la ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_la ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-lastfm"></i></a></div><?php endif; ?>
		<?php if ( $author_fo ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_fo ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-foursquare"></i></a></div><?php endif; ?>
		<?php if ( $author_sp ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_sp ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-spotify"></i></a></div><?php endif; ?>
		<?php if ( $author_vm ) : ?> <div class="molongui-author-box-social-icon"><a href="<?php echo esc_url( $author_vm ); ?>" class="icon-container<?php echo $ico_style; ?> text-size-<?php echo $ico_size; ?>" style="<?php echo $ico_color; ?>;" target="_blank"><i class="icon-ma-vimeo"></i></a></div><?php endif; ?>
	</div>
	<?php endif; ?>

	<!-- Author data -->

	<div class="molongui-table-cell molongui-author-box-data">
		<div class="molongui-author-box-title">
			<?php if ( $author_link ) echo '<a href="' . esc_url( $author_link ) .'" target="_blank">'; ?>
				<h5 class="text-size-<?php echo $options['molongui_authorship_name_size']; ?>" style="color: <?php echo $options['molongui_authorship_name_color']; ?>"><?php echo $author_name; ?></h5>
			<?php if ( $author_link ) echo '</a>'; ?>
		</div>
		<div class="molongui-author-box-job text-size-<?php echo $options['molongui_authorship_meta_size']; ?>" style="color: <?php echo $options['molongui_authorship_meta_color']; ?>">
			<?php if ( $author_company_link ) echo '<a href="' . esc_url( $author_company_link ) . '" target="_blank">'; ?>
				<?php echo $author_job; ?>
				<?php if ( $author_job && $author_company ) echo ' ' . __('at', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) . ' '; ?>
				<?php echo $author_company; ?>
			<?php if ( $author_company_link ) echo '</a>'; ?>
		</div><!-- End molongui-author-box-job -->
		<div class="molongui-author-box-bio">
			<p class="text-size-<?php echo $options['molongui_authorship_bio_size']; ?> text-align-<?php echo $options['molongui_authorship_bio_align']; ?> text-style-<?php echo $options['molongui_authorship_bio_style']; ?>" style="color: <?php echo $options['molongui_authorship_bio_color']; ?>">
				<?php echo $author_bio; ?>
			</p>
		</div><!-- End molongui-author-box-bio -->
	</div><!-- End molongui-author-box-data -->

</div><!-- End molongui-author-box-container -->