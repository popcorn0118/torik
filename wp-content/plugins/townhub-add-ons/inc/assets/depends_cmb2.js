jQuery(document).ready( function($) {

    
    // show / hide portfolio style selection
    if( $('.post-type-portfolio #page_template').val() == 'default') {
        $('.cmb2-id--cth-folio-single-style').show();
        $('.cmb2-id--cth-folio-video').addClass('depend_field_show_1');
    }else{
        $('.cmb2-id--cth-folio-video').addClass('depend_field_hide_1');
    }
    $('.post-type-portfolio #page_template').on('change', function (e) {
        if( $(e.currentTarget).val() == 'default') {
            $('.cmb2-id--cth-folio-single-style').show();
            if( $('#_cth_folio_single_style').val() == 'video') {
                $('.cmb2-id--cth-folio-video').addClass('depend_field_show_3');
            }else{
                $('.cmb2-id--cth-folio-video').removeClass('depend_field_show_3');
            }
        }
        else{
           $('.cmb2-id--cth-folio-single-style').hide();
           $('.cmb2-id--cth-folio-video').removeClass('depend_field_show_3').addClass('depend_field_hide_3');
           //$('.cmb2-id--cth-folio-video').removeClass('depend_field_show_3');
        }         
    });

    // show / hide portfolio video depend on style and template
    if( $('#_cth_folio_single_style').val() == 'video') {
        $('.cmb2-id--cth-folio-video').addClass('depend_field_show_2');
    }else{
        $('.cmb2-id--cth-folio-video').addClass('depend_field_hide_2');
    }
    $('#_cth_folio_single_style').on('change', function (e) {
        if( $('#_cth_folio_single_style').val() == 'video') {
            $('.cmb2-id--cth-folio-video').removeClass('depend_field_hide_3').addClass('depend_field_show_2');
        }
        else{
           $('.cmb2-id--cth-folio-video').removeClass('depend_field_show_2 depend_field_show_3').addClass('depend_field_hide_2');
        }         
    });


    // show / hide portfolio column grid options
    if( $('.post-type-portfolio #page_template').val() == 'single-portfolio-columngrid.php') {
        $('#folio_column_template').show();
    }
    $('.post-type-portfolio #page_template').on('change', function (e) {
        if( $(e.currentTarget).val() == 'single-portfolio-columngrid.php') {
            $('#folio_column_template').show();
        }
        else{
           $('#folio_column_template').hide();
        }         
    });

});