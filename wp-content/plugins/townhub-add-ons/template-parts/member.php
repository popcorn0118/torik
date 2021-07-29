<?php
/* add_ons_php */
if(!isset($member)) $member = array();
?>
<div class="team-box-wrap">
    <?php 
    $mem_img = '';
    if(!empty($member['id_image']) && !is_array( $member['id_image'] ) ){
        $mem_img = $member['id_image'];
    }
    if(!empty($mem_img)):
    ?>
    <div class="team-photo">
        <?php echo wp_get_attachment_image( $mem_img, 'full', false, array('class'  => 'respimg') ); ?>
    </div>
    <?php endif; ?>
    <div class="team-info">
        <?php if(!empty($member['name'])): ?>
            <h3>
                <?php if(!empty($member['url'])): ?><a href="<?php echo $member['url']; ?>" rel="nofollow"><?php endif; ?>
                <?php echo $member['name'];?>
                <?php if(!empty($member['url'])): ?></a><?php endif; ?>
            </h3>
        <?php endif; ?>
        <?php if(!empty($member['job'])): ?><h4><?php echo $member['job'];?></h4><?php endif; ?>
        <?php if(!empty($member['desc'])): ?><div class="member-description"><?php echo $member['desc'];?></div><?php endif; ?>
        <?php if(!empty($member['socials'])): ?>
        <div class="team-social">
            <ul class="no-list-style">
            <?php 
            foreach ($member['socials'] as $key => $social): 
                 echo '<li><a href="'.esc_url( $social['url'] ).'" target="_blank" rel="nofollow"><i class="fab fa-'.esc_attr( $social['name'] ).'"></i></a></li>';
            endforeach ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>