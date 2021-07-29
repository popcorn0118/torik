<?php
/* add_ons_php */

if(!isset($data)) $data = false;
if(!isset($active)) $active = false;
?>
<?php if($data === false) : ?>
<!-- chat-contacts-item-->
<a class="chat-item" href="#" data-cid="{{data.cid}}" data-touid="{{data.touid}}" data-fuid="{{data.user_one}}">
    <div class="chat-avatar">
        {{{data.avatar}}}
        <!-- <div class="chat-counter">{{data.count}}</div> -->
    </div>
    <div class="chat-content">
        <h4 class="display_name">{{data.display_name}}</h4>
        <span class="chat-date">{{{data.date}}}</span>
        <div class="chat-reply-text">{{{data.reply}}}</div>
    </div>
</a>
<!-- chat-contacts-item -->
<?php else : ?>
<!-- chat-contacts-item-->
<a class="chat-item<?php if($active) echo ' active';?>" href="#" data-cid="<?php echo esc_attr($data->cid);?>" data-touid="<?php echo esc_attr($data->touid);?>" data-fuid="<?php echo esc_attr($data->user_one);?>">
    <div class="chat-avatar">
        <?php echo $data->avatar; ?>
        <!-- <div class="chat-counter">2</div> -->
    </div>
    <div class="chat-content">
        <h4 class="display_name"><?php echo $data->display_name; ?></h4>
        <span class="chat-date"><?php echo $data->date; ?></span>
        <div class="chat-reply-text"><?php if($data->reply != '') echo $data->reply; ?></div>
    </div>
</a>
<!-- chat-contacts-item -->
<?php endif;



