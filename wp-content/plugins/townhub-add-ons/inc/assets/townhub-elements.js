

function townhub_do_ajax_get_vc_attach_images(images, holder, wrapper){
  jQuery.post({
      dataType: "json",
      url: ajaxurl,
      data: {action: 'townhub_get_vc_attach_images', images: images},
      success:function(result){
          //console.log(result);
          // if(wrapper.find('i.vc_element-icon').length){
          //   wrapper.find('i.vc_element-icon').replaceWith(result);//.remove();
          //   //wrapper.find('.wpb_element_title').append(result);
          // }else if(wrapper.find('.wpb_element_title img').length){
          //   wrapper.find('.wpb_element_title img').replaceWith(result);
          // }else{
            wrapper.find('i.vc_element-icon').remove();
            holder.html(result);
          //}
          
      }
  });
}


window.TownHubImagesView = vc.shortcode_view.extend({
  $wrapper: !1
  , changeShortcodeParams: function (model) {
    var params;
    if (window.TownHubImagesView.__super__.changeShortcodeParams.call(this, model), params = _.extend({}, model.get("params")), this.$wrapper || (this.$wrapper = this.$el.find(".wpb_element_wrapper")), _.isObject(params)){
      var wrapper_jq = this.$wrapper;
      this.$wrapper.find('.wpb_vc_param_value.ajax-vc-img').each(function(){

        var singleimgHolder = jQuery(this);

        var param_name = singleimgHolder.attr('name') ;

        if(param_name !== 'undefined' && "undefined" !== params[param_name] && '' !== params[param_name]){
          townhub_do_ajax_get_vc_attach_images(params[param_name], singleimgHolder, wrapper_jq);
        }

      });
      
    }
  }
});