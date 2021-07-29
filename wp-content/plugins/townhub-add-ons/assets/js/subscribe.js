(function($){
	'use strict';
	$(document).ready(function() {
		$('.townhub_mailchimp-form').each(function(){
			var sub_form = $(this);
			sub_form.submit(function(e){
				e.preventDefault();
				sub_form.find('.subscribe-message').fadeIn('slow').text(_townhub_sub.pl_w);
				var dataString = sub_form.serialize();
		            dataString += '&action=townhub_mailchimp';
		        var noredirect = true;
				$.ajax({
		            type: "POST",
		            data: dataString,
		            url: _townhub_sub.url,
		            success: function(d) {
		            	console.log(d);
		                if(d.success){
		                	if (d.success === 'yes') {
			                    if (noredirect) {
			                    	sub_form.find('.subscribe-message').fadeIn('slow').text(d.message);//.delay(3000).fadeOut('slow');
			                    } 
			                    else {
			                        window.location.href = redirect;
			                    }
			                } else {
			                	sub_form.find('.subscribe-message').fadeIn('slow').text(d.message);//.delay(3000).fadeOut('slow');
			                	console.log(d.last_response);
			                }
		                }
		            }
		        });

			});
		});

	});

})(jQuery);