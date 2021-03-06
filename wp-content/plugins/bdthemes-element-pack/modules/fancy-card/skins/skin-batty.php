<?php

namespace ElementPack\Modules\FancyCard\Skins;
use ElementPack\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use ElementPack\Utils;
use Elementor\Icons_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
 

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

class Skin_Batty extends Elementor_Skin_Base
{

    public function get_id()
    {
        return 'batty';
    }

    public function get_title()
    {
        return __('Batty', 'bdthemes-element-pack');
    }

    public function render()
    {
       
		$settings  = $this->parent->get_settings_for_display();

		$has_icon  = ! empty( $settings['icon'] );
		$has_image = ! empty( $settings['image']['url'] );

		if ( $has_icon and 'icon' == $settings['icon_type'] ) {
			$this->parent->add_render_attribute( 'font-icon', 'class', $settings['selected_icon'] );
			$this->parent->add_render_attribute( 'font-icon', 'aria-hidden', 'true' );			
		} elseif ( $has_image and 'image' == $settings['icon_type'] ) {
			$this->parent->add_render_attribute( 'image-icon', 'src', $settings['image']['url'] );
			$this->parent->add_render_attribute( 'image-icon', 'alt', $settings['title_text'] );
		}

		$this->parent->add_render_attribute( 'gradient-card', 'class', 'bdt-fancy-card bdt-fancy-card-skin-batty' );
		$this->parent->add_render_attribute( 'description_text', 'class', 'bdt-fancy-card-description' );

		// $this->parent->add_render_attribute( 'title_text', 'none' );
		// $this->parent->add_render_attribute( 'description_text' );


		$this->parent->add_render_attribute( 'readmore', 'class', ['bdt-fancy-card-readmore', 'bdt-display-inline-block'] );
		
		if ( ! empty( $settings['readmore_link']['url'] ) ) {
			$this->parent->add_render_attribute( 'readmore', 'href', $settings['readmore_link']['url'] );

			if ( $settings['readmore_link']['is_external'] ) {
				$this->parent->add_render_attribute( 'readmore', 'target', '_blank' );
			}

			if ( $settings['readmore_link']['nofollow'] ) {
				$this->parent->add_render_attribute( 'readmore', 'rel', 'nofollow' );
			}

		}

		if ($settings['readmore_attention']) {
			$this->parent->add_render_attribute( 'readmore', 'class', 'bdt-ep-attention-button' );
		}		

		if ( $settings['readmore_hover_animation'] ) {
			$this->parent->add_render_attribute( 'readmore', 'class', 'elementor-animation-' . $settings['readmore_hover_animation'] );
		}

		if ($settings['onclick']) {
			$this->parent->add_render_attribute( 'readmore', 'onclick', $settings['onclick_event'] );
		}

		$this->parent->add_render_attribute( 'bdt-fancy-card-title', 'class', 'bdt-fancy-card-title' );
		
		if ('yes' == $settings['title_link'] and $settings['title_link_url']['url']) {

			$target = $settings['title_link_url']['is_external'] ? '_blank' : '_self';

			$this->parent->add_render_attribute( 'bdt-fancy-card-title', 'onclick', "window.open('" . $settings['title_link_url']['url'] . "', '$target')" );
		}
		
		if ('yes' == $settings['global_link'] and $settings['global_link_url']['url']) {

			$target = $settings['global_link_url']['is_external'] ? '_blank' : '_self';

			$this->parent->add_render_attribute( 'gradient-card', 'onclick', "window.open('" . $settings['global_link_url']['url'] . "', '$target')" );
		}
		
		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}

 
		?>
 

		<div <?php echo $this->parent->get_render_attribute_string( 'gradient-card' ); ?>>
			<div class="bdt-batty-face bdt-batty-face1">
				<div class="bdt-fancy-card-content">
				 
					<?php if ( $settings['title_text'] ) : ?>
							<<?php echo Utils::get_valid_html_tag($settings['title_size']); ?> <?php echo $this->parent->get_render_attribute_string( 'bdt-fancy-card-title' ); ?>>
								<span <?php //echo $this->parent->get_render_attribute_string( 'title_text' ); ?>>
									<?php echo wp_kses( $settings['title_text'], element_pack_allow_tags('title') ); ?>
								</span>
							</<?php echo Utils::get_valid_html_tag($settings['title_size']); ?>>
						<?php endif; ?>
			 
				 
					<?php if ( $settings['description_text'] ) : ?>
							<div <?php echo $this->parent->get_render_attribute_string( 'description_text' ); ?>>
								<?php echo wp_kses( $settings['description_text'], element_pack_allow_tags('text') ); ?>
							</div>
						<?php endif; ?>
						<?php if ($settings['readmore']) : ?>
							<a <?php echo $this->parent->get_render_attribute_string( 'readmore' ); ?>>
								<?php echo esc_html($settings['readmore_text']); ?>
								
								<?php if ($settings['advanced_readmore_icon']['value']) : ?>

									<span class="bdt-button-icon-align-<?php echo $settings['readmore_icon_align'] ?>">

										<?php Icons_Manager::render_icon( $settings['advanced_readmore_icon'], [ 'aria-hidden' => 'true', 'class' => 'fa-fw' ] ); ?>
									
									</span>

								<?php endif; ?>
							</a>
						<?php endif ?>
					 
				</div>
			</div>
			<div class="bdt-batty-face bdt-batty-face2">


			<?php if ( $has_icon or $has_image ) : ?>
						<div class="bdt-fancy-card-icon" data-label="<?php echo $settings['title_text']; ?>">
							<span class="bdt-icon-wrapper">
								<?php if ( $has_icon and 'icon' == $settings['icon_type'] ) { ?>

									<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>

								<?php } elseif ( $has_image and 'image' == $settings['icon_type'] ) { ?>
									<img <?php echo $this->parent->get_render_attribute_string( 'image-icon' ); ?>>
								<?php } ?>
							</span>
						</div>
					<?php endif; ?>
			</div>
		</div>
		
		<?php if ( $settings['indicator'] ) : ?>
			<div class="bdt-indicator-svg bdt-svg-style-<?php echo esc_attr($settings['indicator_style']); ?>">
				<?php echo element_pack_svg_icon('arrow-' . $settings['indicator_style']); ?>
			</div>
			<?php endif; ?>

			<?php if ( $settings['badge'] and '' != $settings['badge_text'] ) : ?>
			<div class="bdt-fancy-card-badge bdt-position-<?php echo esc_attr($settings['badge_position']); ?>">
				<span class="bdt-badge bdt-padding-small"><?php echo esc_html($settings['badge_text']); ?></span>
			</div>
		<?php endif; ?>
		
        <?php
}

}
