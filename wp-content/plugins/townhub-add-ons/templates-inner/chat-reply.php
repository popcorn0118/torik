<?php
/* add_ons_php */

if(!isset($data)) $data = false;
?>
<?php if($data === false) : ?>
<!-- message--> 
<div class="chat-reply chat-reply-{{data.crid}}<# if(data.uid != data.user_one){ #> chat-reply-reply<# } #><# if(data.uid == data.current_user){ #> your-reply<# } #>">
    <div class="reply-avatar">
        {{{data.avatar}}}
        <span class="display_name">{{data.display_name}}</span>
    </div>
    <div class="reply-content">
	    <span class="reply-time">{{{data.time}}}</span>
	    <div class="reply-text">{{{data.reply}}}</div>
	</div>
</div>
<!-- message end--> 
<?php else : ?><!-- message--> 
<div class="chat-reply chat-reply-<?php echo $data->crid;?><?php if($data->uid != $data->user_one) echo ' chat-reply-reply';?><?php if($data->uid == $data->current_user) echo ' your-reply';?>">
    <div class="reply-avatar">
        <?php echo $data->avatar; ?>
        <span class="display_name"><?php echo $data->display_name; ?></span>
    </div>
	<div class="reply-content">
	    <span class="reply-time"><?php echo $data->time; ?></span>
	    <div class="reply-text"><?php echo $data->reply; ?></div>
    </div>
</div>
<!-- message end--> 
<?php endif; 
