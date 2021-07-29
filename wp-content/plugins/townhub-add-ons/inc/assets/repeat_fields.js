(function($){
    'use strict';
    $(function(){

        $('.js-example-basic-multiple').select2();
        $('.js-select2-single').select2();

        $('.cth-icons-wrap').on('input','input',function(e){
            e.preventDefault();
            var icon = $(this).val()
            if(icon != ''){
                $(this).closest('.cth-icons-wrap').find('.cthicon-select').hide();
                $(this).closest('.cth-icons-wrap').find('.cthicon-select').filter('[data-font*="'+icon+'"]').show();
            }else{
                $(this).closest('.cth-icons-wrap').find('.cthicon-select').show();
            }
            $(this).closest('.cth-icons-wrap').find('.cth-icons-hold').removeClass('off_select').css('display','flex');
        });
        $(document).on('click','.cthicon-select',function(e){
            e.preventDefault();
            $(this).closest('.cth-icons-hold').addClass('off_select').css('display','none');
            var icon = $(this).data('font');
            $(this).closest('.cth-icons-wrap').children('input').val(icon)
            $(this).closest('.cth-icons-wrap').children('.cth-icon-preview').children('i').attr('class', icon +' fa-2x')
        })

        
        $(document).on('click','.repeatable_add_option',function(event){
            event.preventDefault();
            //alert('1');
            $this = $(this);
            $thistr = $this.closest('tr');
            field_name = $this.attr('data-name');
            key_val = parseInt($thistr.prev('tr').attr('data-key'))+1;
            $thistr.before('<tr data-key="'+key_val+'"><td><input type="text" name="'+field_name+'['+key_val+'][name]" ></td><td><input type="text" name="'+field_name+'['+key_val+'][value]"  ></td><td><a href="#" class="repeatable_remove_option"><span class="dashicons dashicons-no"></span></a></td></tr>');
        });
        $(document).on('click','.repeatable_remove_option',function(event){
            event.preventDefault();
            $this = $(this);
            $thistr = $this.closest('tr');
            $thistr.remove();
        });
        //For repeateable_fields
        $(document).on('click','.repeatable_fields_add_field',function(event){
            event.preventDefault();
            //alert('1');
            var $this = $(this),
                $currentTr = $this.closest('.repeatfield_table').children('tbody').children('tr:last-child'), 
                field_name = $this.attr('data-name'),
                key_val = parseInt($currentTr.attr('data-key'))+1;
            // key_val = '';
            // $thistr.before('<tr data-key="'+key_val+'"><td><select name="'+field_name+'['+key_val+'][type]" class="select_field_type" data-name="'+field_name+'['+key_val+']" data-type="text"><option value="text" selected="selected">Text Field</option><option value="select">Select Field</option><option value="textarea">Textarea Field</option></select></td><td><input type="text" name="'+field_name+'['+key_val+'][name]" placeholder="Field Name"></td><td><input type="text" name="'+field_name+'['+key_val+'][label]"  placeholder="Field Label"></td><td class="field_values_col"><input type="text" name="'+field_name+'['+key_val+'][value]"  placeholder="Field Value"></td><td><input type="checkbox" name="'+field_name+'['+key_val+'][required]" value="true"/>Required Field?</td><td><a href="#" class="repeatable_fields_remove_field"><span class="dashicons dashicons-no"></span></a></td></tr>');
            $currentTr.after('<tr data-key="'+key_val+'"><td><select name="'+field_name+'['+key_val+'][type]" class="select_field_type" data-name="'+field_name+'['+key_val+']" data-type="text"><option value="text" selected="selected">Text Field</option><option value="select">Select Field</option><option value="checkbox">Checkbox Field</option><option value="radio">Radio Field</option><option value="switch">Switch Field</option><option value="textarea">Textarea Field</option></select></td><td><input type="text" name="'+field_name+'['+key_val+'][name]" placeholder="Field Name"></td><td><input type="text" name="'+field_name+'['+key_val+'][label]"  placeholder="Field Label"></td><td class="field_values_col"><input type="text" name="'+field_name+'['+key_val+'][value]"  placeholder="Field Value"></td><td><a href="#" class="repeatable_fields_remove_field"><span class="dashicons dashicons-trash"></span></a></td></tr>');
            //trigger field type change
            // $('.select_field_type').trigger('change');
        });
        $(document).on('click','.repeatable_fields_remove_field',function(event){
            event.preventDefault();
            var $this = $(this),
                $thistr = $this.closest('tr');
            $thistr.remove();
        });
        $(document).on('click','.repeatable_fields_select_add_option',function(event){
            event.preventDefault();
            //alert('1');
            var $this = $(this),
                $thistr = $this.closest('tr'),
                field_name = $this.attr('data-name'),
                key_val = parseInt($thistr.prev('tr').attr('data-key'))+1;
            $thistr.before('<tr data-key="'+key_val+'"><td><input type="text" name="'+field_name+'['+key_val+'][name]" placeholder="Option Name"></td><td><input type="text" name="'+field_name+'['+key_val+'][value]" placeholder="Option Value"></td><td><a href="#" class="repeatable_fields_select_remove_option"><span class="dashicons dashicons-minus"></span></a></td></tr>');
        });
        $(document).on('click','.repeatable_fields_select_remove_option',function(event){
            event.preventDefault();
            var $this = $(this),
                $thistr = $this.closest('tr');
            $thistr.remove();
        });
        $(document).on('change','.select_field_type',function(){
            var $this = $(this),
                $type = $this.val(),
                $fname = $this.data('name'),
            
                $valuetd = $this.closest('tr').children('.field_values_col'),
                $old_type = $this.data('type');
            if($old_type == 'text'||$old_type == 'checkbox'||$old_type == 'switch'){
                $this.data('text-value',$valuetd.children().val());
            }else if($old_type == 'textarea'){
                $this.data('textarea-value',$valuetd.children().val());
            }else if($old_type == 'select'||$old_type == 'radio'){
                // console.log($valuetd.find('input').length);
                var select_ops_value = [];
                if($valuetd.find('input').length){
                    // select_ops_value = [];
                    var count = 0;
                    var mid_arr = {};
                    $valuetd.find('input').each(function(index,value){
                        if((index+1)%2 === 0){
                            // console.log('value '+index);
                            // console.log($(this));
                            // select_ops_value[count] = ['value',$(value).val()? $(value).val() : ''];
                            mid_arr.value = $(value).val()? $(value).val() : '';
                            select_ops_value[count] = mid_arr;
                            mid_arr = {};
                            count++;
                        }else{
                            // console.log('name '+index);
                            // console.log(value);
                            // select_ops_value[count] = ['name',$(value).val()? $(value).val() : ''];
                            mid_arr.name = $(value).val()? $(value).val() : '';
                        }
                    });
                }
                // console.log(select_ops_value);
                // console.log(JSON.stringify(select_ops_value));
                $this.data('select-value',JSON.stringify(select_ops_value));
            }
            $this.data('type',$type);
            switch($type){
                case 'text':
                case 'checkbox':
                case 'switch':
                    $valuetd.replaceWith('<td class="field_values_col"><input type="text" name="'+$fname+'[value]" placeholder="Field Value" value="'+($this.data('text-value')? $this.data('text-value') : '')+'"></td>');
                    break;

                case 'textarea':
                    // $valuetd.replaceWith('<td class="field_values_col"><input type="text" name="'+$fname+'[value]" placeholder="Field Value"  value="'+($this.data('textarea-value')? $this.data('textarea-value') : '')+'"></td>');
                    $valuetd.replaceWith('<td class="field_values_col"><textarea name="'+$fname+'[value]" placeholder="Field Value">'+($this.data('textarea-value')? $this.data('textarea-value') : '')+'</textarea></td>');
                    break;

                case 'select':
                case 'radio':
                
                    var select_ops_value_parse = $this.data('select-value') ? JSON.parse($this.data('select-value')) : [];
                    // console.log(select_ops_value_parse);
                    if(select_ops_value_parse.length){
                        var select_ops_input = '';
                        for (var i = 0; i < select_ops_value_parse.length; i++) {
                            select_ops_input += '<tr data-key="'+i+'"><td><input type="text" name="'+$fname+'[value]['+i+'][name]" placeholder="Option Name" value="'+select_ops_value_parse[i]['name']+'"></td><td><input type="text" name="'+$fname+'[value]['+i+'][value]" placeholder="Option Value" value="'+select_ops_value_parse[i]['value']+'"></td><td><a href="#" class="repeatable_fields_select_remove_option"><span class="dashicons dashicons-minus"></span></a></td></tr>';
                            //select_ops_value_parse[i]
                        };
                        $valuetd.replaceWith('<td class="field_values_col field_select_ops"><table><tbody><tr data-key="0" class="hide"></tr>'+select_ops_input+'<tr><td><a href="#" class="repeatable_fields_select_add_option" data-name="'+$fname+'[value]"><span class="dashicons dashicons-plus"></span></a></td><td></td><td></td></tr></tbody></table></td>');
                    }else{
                        $valuetd.replaceWith('<td class="field_values_col field_select_ops"><table><tbody><tr data-key="0" class="hide"></tr><tr><td><a href="#" class="repeatable_fields_select_add_option" data-name="'+$fname+'[value]"><span class="dashicons dashicons-plus"></span></a></td><td></td><td></td></tr></tbody></table></td>');
                    }
                    // $valuetd.replaceWith('<td class="field_values_col field_select_ops"><table><tbody><tr data-key="0" class="hide"></tr><tr data-key="1"><td><input type="text" name="'+$fname+'[value][0][name]" placeholder="Option Name"></td><td><input type="text" name="'+$fname+'[value][0][value]" placeholder="Option Value"></td><td><a href="#" class="repeatable_fields_select_remove_option"><span class="dashicons dashicons-no"></span></a></td></tr><tr><td><a href="#" class="repeatable_fields_select_add_option" data-name="'+$fname+'[value]"><span class="dashicons dashicons-plus"></span></a></td><td></td><td></td></tr></tbody></table></td>');
                    // $('.repeatable_fields_select_add_option').trigger('click');
                    break;

            }
        });

        $( ".repeatfield_table tbody" ).sortable();
        $( ".content-widgets-wrap" ).sortable();
        $( ".sidebar-widgets-wrap" ).sortable();

        

    })
})(jQuery);