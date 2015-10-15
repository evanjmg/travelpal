<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package ThemeGrill
 * @subpackage Radiate
 * @since Radiate 1.0
 */
?>

		</div><!-- .inner-wrap -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'radiate_credits' ); ?>

 © 2015 Travelpal | <a href="http://www.uktravelpal.com/privacy-policy/" target="_blank">Privacy Policy</a> | <a href="https://instagram.com/uktravelpal/">Instagram</a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
   <a href="#masthead" id="scroll-up"><span class="genericon genericon-collapse"></span></a>
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/js/foundation.min.js"></script>
<script>
jQuery('.entry-summary > .addthis_toolbox:first-child').next('.addthis_toolbox').hide();
	jQuery('.byline').hide();
</script>
</body>
</html>