<?php
if (!isset($product_id)) {
    return;
}

?>
<div class="dashboard-content-wrapper dashboard-content-product-edit">
            <div class="dashboard-list-box fl-wrap">

                <div class="dashboard-title fl-wrap dashboard-edit-title"><h3>Edit Product</h3></div>
                <div class="dasboard-wrap dashboard-submit-wrapper fl-wrap">
                        <form class="submit-listing-form">
                    <div class="submit-fields-wrap">
                        <div class="submit-field submit-field-dashboard submit-field-input submit-field-title submit-field-12">
                            <div class="lfield-header">
                                <span class="lfield-icon">
                                    <i class="fal fa-briefcase">
                                    </i>
                                </span>
                                <label class="lfield-label">
                                    Product Title
                                </label>
                            </div>
                            <div class="lfield-content hasIcon" id="subfield_content_title">
                                <input id="esb_subfield_title" name="title" placeholder="" required="" type="text" value="Testing product"/>
                            </div>
                        </div>
                        <div class="submit-field submit-field-dashboard submit-field-woocats submit-field-cats submit-field-6">
                            <div class="lfield-header">
                                <label class="lfield-label">
                                    Category
                                </label>
                            </div>
                            <div class="lfield-content">
                                <div class=" css-2b097c-container">
                                    <div class=" css-1kk2x7d-control">
                                        <div class=" css-1hwfws3">
                                            <div class=" css-bqye3l-singleValue">
                                                Hoodies
                                            </div>
                                            <div class="css-1g6gooi">
                                                <div class="" style="display: inline-block;">
                                                    <input aria-autocomplete="list" autocapitalize="none" autocomplete="off" autocorrect="off" id="react-select-4-input" spellcheck="false" style="box-sizing: content-box; width: 2px; background: 0px center; border: 0px; font-size: inherit; opacity: 1; outline: 0px; padding: 0px; color: inherit;" tabindex="0" type="text" value="">
                                                        <div style="position: absolute; top: 0px; left: 0px; visibility: hidden; height: 0px; overflow: scroll; white-space: pre; font-size: 14px; font-family: Arial; font-weight: 400; font-style: normal; letter-spacing: normal; text-transform: none;">
                                                        </div>
                                                    </input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" css-1wy0on6">
                                            <span class=" css-1okebmr-indicatorSeparator">
                                            </span>
                                            <div aria-hidden="true" class=" css-tlfecz-indicatorContainer">
                                                <svg aria-hidden="true" class="css-19bqh2r" focusable="false" height="20" viewbox="0 0 20 20" width="20">
                                                    <path d="M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lfield-desc">
                            </div>
                        </div>
                        <div class="submit-field submit-field-dashboard submit-field-textarea submit-field-post_excerpt submit-field-12">
                            <div class="lfield-header">
                                <label class="lfield-label">
                                    Short Description
                                </label>
                            </div>
                            <div class="lfield-content">
                                <textarea cols="40" name="post_excerpt" rows="3">
                                    This is dummy product by cththemes yrdy
                                </textarea>
                            </div>
                            <div class="lfield-desc">
                            </div>
                        </div>
                        <div class="submit-field submit-field-dashboard submit-field-editor submit-field-content submit-field-12">
                            <div class="lfield-header">
                                <label class="lfield-label">
                                    Description
                                </label>
                            </div>
                            <div class="lfield-content">
                                <div class="wp-core-ui wp-editor-wrap tmce-active" id="wp-wpeditor_content-wrap">
                                    <div class="wp-editor-tools">
                                        <div class="wp-editor-tabs">
                                            <button class="wp-switch-editor switch-tmce" data-wp-editor-id="wpeditor_content" id="wpeditor_content-tmce" type="button">
                                                Visual
                                            </button>
                                            <button class="wp-switch-editor switch-html" data-wp-editor-id="wpeditor_content" id="wpeditor_content-html" type="button">
                                                Text
                                            </button>
                                        </div>
                                        <div class="wp-editor-container">
                                            <div class="mce-tinymce mce-container mce-panel" hidefocus="1" id="mceu_40" role="application" style="visibility: hidden; border-width: 1px; width: 100%;" tabindex="-1">
                                                <div class="mce-container-body mce-stack-layout" id="mceu_40-body">
                                                    <div class="mce-top-part mce-container mce-stack-layout-item mce-first" id="mceu_41">
                                                        <div class="mce-container-body" id="mceu_41-body">
                                                            <div class="mce-toolbar-grp mce-container mce-panel mce-first mce-last" hidefocus="1" id="mceu_42" role="group" tabindex="-1">
                                                                <div class="mce-container-body mce-stack-layout" id="mceu_42-body">
                                                                    <div class="mce-container mce-toolbar mce-stack-layout-item mce-first mce-last" id="mceu_43" role="toolbar">
                                                                        <div class="mce-container-body mce-flow-layout" id="mceu_43-body">
                                                                            <div class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" id="mceu_44" role="group">
                                                                                <div id="mceu_44-body">
                                                                                    <div aria-label="Bold (⌘B)" aria-pressed="false" class="mce-widget mce-btn mce-first" id="mceu_35" role="button" tabindex="-1">
                                                                                        <button id="mceu_35-button" role="presentation" tabindex="-1" type="button">
                                                                                            <i class="mce-ico mce-i-bold">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div aria-label="Italic (⌘I)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_36" role="button" tabindex="-1">
                                                                                        <button id="mceu_36-button" role="presentation" tabindex="-1" type="button">
                                                                                            <i class="mce-ico mce-i-italic">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div aria-label="Bulleted list (⌃⌥U)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_37" role="button" tabindex="-1">
                                                                                        <button id="mceu_37-button" role="presentation" tabindex="-1" type="button">
                                                                                            <i class="mce-ico mce-i-bullist">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div aria-label="Numbered list (⌃⌥O)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_38" role="button" tabindex="-1">
                                                                                        <button id="mceu_38-button" role="presentation" tabindex="-1" type="button">
                                                                                            <i class="mce-ico mce-i-numlist">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div aria-label="Insert/edit link (⌘K)" aria-pressed="false" class="mce-widget mce-btn mce-last" id="mceu_39" role="button" tabindex="-1">
                                                                                        <button id="mceu_39-button" role="presentation" tabindex="-1" type="button">
                                                                                            <i class="mce-ico mce-i-link">
                                                                                            </i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mce-edit-area mce-container mce-panel mce-stack-layout-item" hidefocus="1" id="mceu_45" role="group" style="border-width: 1px 0px 0px;" tabindex="-1">
                                                        <iframe allowtransparency="true" frameborder="0" id="wpeditor_content_ifr" style="width: 100%; height: 100px; display: block;" title="Rich Text Area. Press Control-Option-H for help.">
                                                        </iframe>
                                                    </div>
                                                    <div class="mce-statusbar mce-container mce-panel mce-stack-layout-item mce-last" hidefocus="1" id="mceu_46" role="group" style="border-width: 1px 0px 0px;" tabindex="-1">
                                                        <div class="mce-container-body mce-flow-layout" id="mceu_46-body">
                                                            <div class="mce-path mce-flow-layout-item mce-first" id="mceu_47">
                                                                <div class="mce-path-item">
                                                                </div>
                                                            </div>
                                                            <div class="mce-flow-layout-item mce-last mce-resizehandle" id="mceu_48">
                                                                <i class="mce-ico mce-i-resize">
                                                                </i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="quicktags-toolbar" id="qt_wpeditor_content_toolbar">
                                                <input aria-label="Bold" class="ed_button button button-small" id="qt_wpeditor_content_strong" type="button" value="b">
                                                    <input aria-label="Italic" class="ed_button button button-small" id="qt_wpeditor_content_em" type="button" value="i">
                                                        <input aria-label="Insert link" class="ed_button button button-small" id="qt_wpeditor_content_link" type="button" value="link">
                                                            <input aria-label="Bulleted list" class="ed_button button button-small" id="qt_wpeditor_content_ul" type="button" value="ul">
                                                                <input aria-label="Numbered list" class="ed_button button button-small" id="qt_wpeditor_content_ol" type="button" value="ol">
                                                                    <input aria-label="List item" class="ed_button button button-small" id="qt_wpeditor_content_li" type="button" value="li">
                                                                        <input aria-label="Code" class="ed_button button button-small" id="qt_wpeditor_content_code" type="button" value="code"/>
                                                                    </input>
                                                                </input>
                                                            </input>
                                                        </input>
                                                    </input>
                                                </input>
                                            </div>
                                            <textarea aria-hidden="true" class="textareaED" id="wpeditor_content" name="content" style="display: none;">
                                                <p>This is dummy product by cththemes</p>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit-field submit-field-dashboard submit-field-image submit-field-thumbnail submit-field-4">
                            <div class="lfield-header">
                                <label class="lfield-label">
                                    Featured Image
                                </label>
                            </div>
                            <div class="lfield-content">
                                <div class="mdimgs-wrap img-cols-1 media-limit-3">
                                    <ul>
                                        <li class="image-sec-item" data-id="6922">
                                            <img class="media-img" src="http://localhost:8888/townhub/wp-content/uploads/2019/12/1884062_FcAlcpcaaRHREJHt8KAzdb5UPt8INYh-D6jk0xJD7_Q.jpg">
                                                <button class="del-image" type="button">
                                                    <i class="fal fa-times">
                                                    </i>
                                                </button>
                                            </img>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="lfield-desc">
                            </div>
                        </div>
                        <div class="submit-field submit-field-dashboard submit-field-image submit-field-images submit-field-8">
                            <div class="lfield-header">
                                <label class="lfield-label">
                                    Product Images
                                </label>
                            </div>
                            <div class="lfield-content">
                                <div class="mdimgs-wrap img-cols-1">
                                    <ul class="ul-no-ordered">
                                        <li class="fu-text">
                                            <span>
                                                <i class="fal fa-image">
                                                </i>
                                                Click here to upload
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="lfield-desc">
                            </div>
                        </div>
                        <div class="submit-field submit-field-dashboard submit-field-input submit-field-_price submit-field-12">
                            <div class="lfield-header">
                                <span class="lfield-icon">
                                    <i class="fal fa-dollar-sign">
                                    </i>
                                </span>
                                <label class="lfield-label">
                                    Price
                                </label>
                            </div>
                            <div class="lfield-content hasIcon" id="subfield_content__price">
                                <input id="esb_subfield__price" name="_price" placeholder="" type="text" value=""/>
                            </div>
                        </div>
                        <div class="room-footer-sec-wrap dis-flex">
                            <button class="btn color2-bg" type="submit">
                                Save Changes
                                <i class="fal fa-paper-plane">
                                </i>
                            </button>
                            <a class="btn btn-link">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>

    

                <div class="postbox-container" id="postbox-container-2">
                    <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                        <div class="postbox " id="woocommerce-product-data">
                            <button aria-expanded="true" class="handlediv" type="button">
                                <span class="screen-reader-text">
                                    Toggle panel: Product data
                                </span>
                                <span aria-hidden="true" class="toggle-indicator">
                                </span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>
                                    Product data
                                    <span class="type_box hidden">
                                        —
                                        <label for="product-type">
                                            <select id="product-type" name="product-type">
                                                <optgroup label="Product Type">
                                                    <option value="simple">
                                                        Simple product
                                                    </option>
                                                    <option value="grouped">
                                                        Grouped product
                                                    </option>
                                                    <option value="external">
                                                        External/Affiliate product
                                                    </option>
                                                    <option selected="selected" value="variable">
                                                        Variable product
                                                    </option>
                                                    <option value="listing_cpt">
                                                        Listing Product
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </label>
                                        <label class="show_if_simple tips" for="_virtual" style="display: none;">
                                            Virtual:
                                            <input id="_virtual" name="_virtual" type="checkbox">
                                            </input>
                                        </label>
                                        <label class="show_if_simple tips" for="_downloadable" style="display: none;">
                                            Downloadable:
                                            <input id="_downloadable" name="_downloadable" type="checkbox">
                                            </input>
                                        </label>
                                    </span>
                                </span>
                            </h2>
                            <div class="inside">
                                <input id="woocommerce_meta_nonce" name="woocommerce_meta_nonce" type="hidden" value="12f602c4dc">
                                    <input name="_wp_http_referer" type="hidden" value="/townhub/wp-admin/post.php?post=45&action=edit">
                                        <div class="panel-wrap product_data">
                                            <ul class="product_data_tabs wc-tabs">
                                                <li class="general_options general_tab hide_if_grouped" style="display: none;">
                                                    <a href="#general_product_data">
                                                        <span>
                                                            General
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="inventory_options inventory_tab show_if_simple show_if_variable show_if_grouped show_if_external active" style="display: block;">
                                                    <a href="#inventory_product_data">
                                                        <span>
                                                            Inventory
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="shipping_options shipping_tab hide_if_virtual hide_if_grouped hide_if_external">
                                                    <a href="#shipping_product_data">
                                                        <span>
                                                            Shipping
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="linked_product_options linked_product_tab">
                                                    <a href="#linked_product_data">
                                                        <span>
                                                            Linked Products
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="attribute_options attribute_tab">
                                                    <a href="#product_attributes">
                                                        <span>
                                                            Attributes
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="variations_options variations_tab variations_tab show_if_variable" style="display: block;">
                                                    <a href="#variable_product_options">
                                                        <span>
                                                            Variations
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="advanced_options advanced_tab">
                                                    <a href="#advanced_product_data">
                                                        <span>
                                                            Advanced
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="marketplace-suggestions_options marketplace-suggestions_tab">
                                                    <a href="#marketplace_suggestions">
                                                        <span>
                                                            Get more options
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="wc-tab-panel panel woocommerce_options_panel" id="general_product_data" style="display: none;">
                                                <div class="options_group show_if_external" style="display: none;">
                                                    <p class="form-field _product_url_field ">
                                                        <label for="_product_url">
                                                            Product URL
                                                        </label>
                                                        <input class="short" id="_product_url" name="_product_url" placeholder="https://" style="" type="text" value="">
                                                            <span class="description">
                                                                Enter the external URL to the product.
                                                            </span>
                                                        </input>
                                                    </p>
                                                    <p class="form-field _button_text_field ">
                                                        <label for="_button_text">
                                                            Button text
                                                        </label>
                                                        <input class="short" id="_button_text" name="_button_text" placeholder="Buy product" style="" type="text" value="">
                                                            <span class="description">
                                                                This text will be shown on the button linking to the external product.
                                                            </span>
                                                        </input>
                                                    </p>
                                                </div>
                                                <div class="options_group pricing show_if_simple show_if_external hidden" style="display: none;">
                                                    <p class="form-field _regular_price_field ">
                                                        <label for="_regular_price">
                                                            Regular price ($)
                                                        </label>
                                                        <input class="short wc_input_price" id="_regular_price" name="_regular_price" placeholder="" style="" type="text" value="">
                                                        </input>
                                                    </p>
                                                    <p class="form-field _sale_price_field ">
                                                        <label for="_sale_price">
                                                            Sale price ($)
                                                        </label>
                                                        <input class="short wc_input_price" id="_sale_price" name="_sale_price" placeholder="" style="" type="text" value="">
                                                            <span class="description">
                                                                <a class="sale_schedule" href="#">
                                                                    Schedule
                                                                </a>
                                                            </span>
                                                        </input>
                                                    </p>
                                                    <p class="form-field sale_price_dates_fields" style="display: none;">
                                                        <label for="_sale_price_dates_from">
                                                            Sale price dates
                                                        </label>
                                                        <input class="short hasDatepicker" id="_sale_price_dates_from" maxlength="10" name="_sale_price_dates_from" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="From… YYYY-MM-DD" type="text" value="">
                                                            <input class="short hasDatepicker" id="_sale_price_dates_to" maxlength="10" name="_sale_price_dates_to" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="To…  YYYY-MM-DD" type="text" value="">
                                                                <a class="description cancel_sale_schedule" href="#">
                                                                    Cancel
                                                                </a>
                                                                <span class="woocommerce-help-tip">
                                                                </span>
                                                            </input>
                                                        </input>
                                                    </p>
                                                </div>
                                                <div class="options_group show_if_downloadable hidden" style="display: none;">
                                                    <div class="form-field downloadable_files">
                                                        <label>
                                                            Downloadable files
                                                        </label>
                                                        <table class="widefat">
                                                            <thead>
                                                                <tr>
                                                                    <th class="sort">
                                                                    </th>
                                                                    <th>
                                                                        Name
                                                                        <span class="woocommerce-help-tip">
                                                                        </span>
                                                                    </th>
                                                                    <th colspan="2">
                                                                        File URL
                                                                        <span class="woocommerce-help-tip">
                                                                        </span>
                                                                    </th>
                                                                    <th>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="ui-sortable" style="">
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="5">
                                                                        <a class="button insert" data-row='
                            <tr>
    <td class="sort"></td>
    <td class="file_name">
        <input type="text" class="input_text" placeholder="File name" name="_wc_file_names[]" value="" />
        <input type="hidden" name="_wc_file_hashes[]" value="" />
    </td>
    <td class="file_url"><input type="text" class="input_text" placeholder="http://" name="_wc_file_urls[]" value="" /></td>
    <td class="file_url_choose" width="1%"><a href="#" class="button upload_file_button" data-choose="Choose file" data-update="Insert file URL">Choose file</a></td>
    <td width="1%"><a href="#" class="delete">Delete</a></td>
</tr>
                            ' href="#">
                                                                            Add File
                                                                        </a>
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <p class="form-field _download_limit_field ">
                                                        <label for="_download_limit">
                                                            Download limit
                                                        </label>
                                                        <input class="short" id="_download_limit" min="0" name="_download_limit" placeholder="Unlimited" step="1" style="" type="number" value="0">
                                                            <span class="description">
                                                                Leave blank for unlimited re-downloads.
                                                            </span>
                                                        </input>
                                                    </p>
                                                    <p class="form-field _download_expiry_field ">
                                                        <label for="_download_expiry">
                                                            Download expiry
                                                        </label>
                                                        <input class="short" id="_download_expiry" min="0" name="_download_expiry" placeholder="Never" step="1" style="" type="number" value="0">
                                                            <span class="description">
                                                                Enter the number of days before a download link expires, or leave blank.
                                                            </span>
                                                        </input>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="wc-tab-panel panel woocommerce_options_panel hidden" id="inventory_product_data" style="display: block;">
                                                <div class="options_group">
                                                    <p class="form-field _sku_field ">
                                                        <label for="_sku">
                                                            <abbr title="Stock Keeping Unit">
                                                                SKU
                                                            </abbr>
                                                        </label>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                        <input class="short" id="_sku" name="_sku" placeholder="" style="" type="text" value="woo-hoodie">
                                                        </input>
                                                    </p>
                                                    <p class="form-field _manage_stock_field show_if_simple show_if_variable" style="display: block;">
                                                        <label for="_manage_stock">
                                                            Manage stock?
                                                        </label>
                                                        <input class="checkbox" id="_manage_stock" name="_manage_stock" style="" type="checkbox" value="yes">
                                                            <span class="description">
                                                                Enable stock management at product level
                                                            </span>
                                                        </input>
                                                    </p>
                                                    <div class="stock_fields show_if_simple show_if_variable" style="display: none;">
                                                        <p class="form-field _stock_field ">
                                                            <label for="_stock">
                                                                Stock quantity
                                                            </label>
                                                            <span class="woocommerce-help-tip">
                                                            </span>
                                                            <input class="short wc_input_stock" id="_stock" name="_stock" placeholder="" step="any" style="" type="number" value="0">
                                                            </input>
                                                        </p>
                                                        <input name="_original_stock" type="hidden" value="0">
                                                            <p class=" form-field _backorders_field">
                                                                <label for="_backorders">
                                                                    Allow backorders?
                                                                </label>
                                                                <span class="woocommerce-help-tip">
                                                                </span>
                                                                <select class="select short" id="_backorders" name="_backorders" style="">
                                                                    <option selected="selected" value="no">
                                                                        Do not allow
                                                                    </option>
                                                                    <option value="notify">
                                                                        Allow, but notify customer
                                                                    </option>
                                                                    <option value="yes">
                                                                        Allow
                                                                    </option>
                                                                </select>
                                                            </p>
                                                            <p class="form-field _low_stock_amount_field ">
                                                                <label for="_low_stock_amount">
                                                                    Low stock threshold
                                                                </label>
                                                                <span class="woocommerce-help-tip">
                                                                </span>
                                                                <input class="short" id="_low_stock_amount" name="_low_stock_amount" placeholder="2" step="any" style="" type="number" value="">
                                                                </input>
                                                            </p>
                                                        </input>
                                                    </div>
                                                    <p class="stock_status_field hide_if_variable hide_if_external hide_if_grouped form-field _stock_status_field" style="display: none;">
                                                        <label for="_stock_status">
                                                            Stock status
                                                        </label>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                        <select class="select short" id="_stock_status" name="_stock_status" style="">
                                                            <option value="instock">
                                                                In stock
                                                            </option>
                                                            <option selected="selected" value="outofstock">
                                                                Out of stock
                                                            </option>
                                                            <option value="onbackorder">
                                                                On backorder
                                                            </option>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="options_group show_if_simple show_if_variable" style="display: block;">
                                                    <p class="form-field _sold_individually_field show_if_simple show_if_variable" style="display: block;">
                                                        <label for="_sold_individually">
                                                            Sold individually
                                                        </label>
                                                        <input class="checkbox" id="_sold_individually" name="_sold_individually" style="" type="checkbox" value="yes">
                                                            <span class="description">
                                                                Enable this to only allow one of this item to be bought in a single order
                                                            </span>
                                                        </input>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="wc-tab-panel panel woocommerce_options_panel hidden" id="shipping_product_data" style="display: none;">
                                                <div class="options_group">
                                                    <p class="form-field _weight_field ">
                                                        <label for="_weight">
                                                            Weight (oz)
                                                        </label>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                        <input class="short wc_input_decimal" id="_weight" name="_weight" placeholder="0" style="" type="text" value="1.5">
                                                        </input>
                                                    </p>
                                                    <p class="form-field dimensions_field">
                                                        <label for="product_length">
                                                            Dimensions (in)
                                                        </label>
                                                        <span class="wrap">
                                                            <input class="input-text wc_input_decimal" id="product_length" name="_length" placeholder="Length" size="6" type="text" value="10">
                                                                <input class="input-text wc_input_decimal" id="product_width" name="_width" placeholder="Width" size="6" type="text" value="8">
                                                                    <input class="input-text wc_input_decimal last" id="product_height" name="_height" placeholder="Height" size="6" type="text" value="3">
                                                                    </input>
                                                                </input>
                                                            </input>
                                                        </span>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="options_group">
                                                    <p class="form-field shipping_class_field">
                                                        <label for="product_shipping_class">
                                                            Shipping class
                                                        </label>
                                                        <select class="select short" id="product_shipping_class" name="product_shipping_class">
                                                            <option selected="selected" value="-1">
                                                                No shipping class
                                                            </option>
                                                        </select>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="wc-tab-panel panel woocommerce_options_panel hidden" id="linked_product_data" style="display: none;">
                                                <div class="options_group show_if_grouped" style="display: none;">
                                                    <p class="form-field">
                                                        <label for="grouped_products">
                                                            Grouped products
                                                        </label>
                                                        <select aria-hidden="true" class="wc-product-search select2-hidden-accessible enhanced" data-action="woocommerce_json_search_products" data-exclude="45" data-placeholder="Search for a product…" data-sortable="true" id="grouped_products" multiple="" name="grouped_products[]" style="width: 50%;" tabindex="-1">
                                                        </select>
                                                        <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 50%;">
                                                            <span class="selection">
                                                                <span aria-expanded="false" aria-haspopup="true" class="select2-selection select2-selection--multiple" tabindex="-1">
                                                                    <ul aria-atomic="true" aria-live="polite" aria-relevant="additions removals" class="select2-selection__rendered ui-sortable">
                                                                        <li class="select2-search select2-search--inline">
                                                                            <input aria-autocomplete="list" autocapitalize="none" autocomplete="off" autocorrect="off" class="select2-search__field" placeholder="Search for a product…" role="textbox" spellcheck="false" style="width: 100px;" tabindex="0" type="text"/>
                                                                        </li>
                                                                    </ul>
                                                                </span>
                                                            </span>
                                                            <span aria-hidden="true" class="dropdown-wrapper">
                                                            </span>
                                                        </span>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="options_group">
                                                    <p class="form-field">
                                                        <label for="upsell_ids">
                                                            Upsells
                                                        </label>
                                                        <select aria-hidden="true" class="wc-product-search select2-hidden-accessible enhanced" data-action="woocommerce_json_search_products_and_variations" data-exclude="45" data-placeholder="Search for a product…" id="upsell_ids" multiple="" name="upsell_ids[]" style="width: 50%;" tabindex="-1">
                                                        </select>
                                                        <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 50%;">
                                                            <span class="selection">
                                                                <span aria-expanded="false" aria-haspopup="true" class="select2-selection select2-selection--multiple" tabindex="-1">
                                                                    <ul aria-atomic="true" aria-live="polite" aria-relevant="additions removals" class="select2-selection__rendered">
                                                                        <li class="select2-search select2-search--inline">
                                                                            <input aria-autocomplete="list" autocapitalize="none" autocomplete="off" autocorrect="off" class="select2-search__field" placeholder="Search for a product…" role="textbox" spellcheck="false" style="width: 100px;" tabindex="0" type="text"/>
                                                                        </li>
                                                                    </ul>
                                                                </span>
                                                            </span>
                                                            <span aria-hidden="true" class="dropdown-wrapper">
                                                            </span>
                                                        </span>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                    </p>
                                                    <p class="form-field hide_if_grouped hide_if_external">
                                                        <label for="crosssell_ids">
                                                            Cross-sells
                                                        </label>
                                                        <select aria-hidden="true" class="wc-product-search select2-hidden-accessible enhanced" data-action="woocommerce_json_search_products_and_variations" data-exclude="45" data-placeholder="Search for a product…" id="crosssell_ids" multiple="" name="crosssell_ids[]" style="width: 50%;" tabindex="-1">
                                                        </select>
                                                        <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 50%;">
                                                            <span class="selection">
                                                                <span aria-expanded="false" aria-haspopup="true" class="select2-selection select2-selection--multiple" tabindex="-1">
                                                                    <ul aria-atomic="true" aria-live="polite" aria-relevant="additions removals" class="select2-selection__rendered">
                                                                        <li class="select2-search select2-search--inline">
                                                                            <input aria-autocomplete="list" autocapitalize="none" autocomplete="off" autocorrect="off" class="select2-search__field" placeholder="Search for a product…" role="textbox" spellcheck="false" style="width: 100px;" tabindex="0" type="text"/>
                                                                        </li>
                                                                    </ul>
                                                                </span>
                                                            </span>
                                                            <span aria-hidden="true" class="dropdown-wrapper">
                                                            </span>
                                                        </span>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="wc-tab-panel panel wc-metaboxes-wrapper hidden" id="product_attributes" style="display: none;">
                                                <div class="toolbar toolbar-top">
                                                    <span class="expand-close">
                                                        <a class="expand_all" href="#">
                                                            Expand
                                                        </a>
                                                        /
                                                        <a class="close_all" href="#">
                                                            Close
                                                        </a>
                                                    </span>
                                                    <select class="attribute_taxonomy" name="attribute_taxonomy">
                                                        <option value="">
                                                            Custom product attribute
                                                        </option>
                                                        <option disabled="disabled" value="pa_color">
                                                            color
                                                        </option>
                                                        <option value="pa_size">
                                                            size
                                                        </option>
                                                    </select>
                                                    <button class="button add_attribute" type="button">
                                                        Add
                                                    </button>
                                                </div>
                                                <div class="product_attributes wc-metaboxes ui-sortable">
                                                    <div class="woocommerce_attribute wc-metabox closed taxonomy pa_color" data-taxonomy="pa_color" rel="0">
                                                        <h3 class="">
                                                            <a class="remove_row delete" href="#">
                                                                Remove
                                                            </a>
                                                            <div aria-expanded="true" class="handlediv" title="Click to toggle">
                                                            </div>
                                                            <div class="tips sort">
                                                            </div>
                                                            <strong class="attribute_name">
                                                                color
                                                            </strong>
                                                        </h3>
                                                        <div class="woocommerce_attribute_data wc-metabox-content hidden" style="display: none;">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="attribute_name">
                                                                            <label>
                                                                                Name:
                                                                            </label>
                                                                            <strong>
                                                                                color
                                                                            </strong>
                                                                            <input name="attribute_names[0]" type="hidden" value="pa_color">
                                                                                <input class="attribute_position" name="attribute_position[0]" type="hidden" value="0">
                                                                                </input>
                                                                            </input>
                                                                        </td>
                                                                        <td rowspan="3">
                                                                            <label>
                                                                                Value(s):
                                                                            </label>
                                                                            <select aria-hidden="true" class="multiselect attribute_values wc-enhanced-select select2-hidden-accessible enhanced" data-placeholder="Select terms" multiple="" name="attribute_values[0][]" tabindex="-1">
                                                                                <option selected="selected" value="38">
                                                                                    Blue
                                                                                </option>
                                                                                <option value="58">
                                                                                    Gray
                                                                                </option>
                                                                                <option selected="selected" value="59">
                                                                                    Green
                                                                                </option>
                                                                                <option selected="selected" value="84">
                                                                                    Red
                                                                                </option>
                                                                                <option value="112">
                                                                                    Yellow
                                                                                </option>
                                                                            </select>
                                                                            <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100px;">
                                                                                <span class="selection">
                                                                                    <span aria-expanded="false" aria-haspopup="true" class="select2-selection select2-selection--multiple" tabindex="-1">
                                                                                        <ul aria-atomic="true" aria-live="polite" aria-relevant="additions removals" class="select2-selection__rendered">
                                                                                            <li class="select2-selection__choice" title="Blue">
                                                                                                <span aria-hidden="true" class="select2-selection__choice__remove" role="presentation">
                                                                                                    ×
                                                                                                </span>
                                                                                                Blue
                                                                                            </li>
                                                                                            <li class="select2-selection__choice" title="Green">
                                                                                                <span aria-hidden="true" class="select2-selection__choice__remove" role="presentation">
                                                                                                    ×
                                                                                                </span>
                                                                                                Green
                                                                                            </li>
                                                                                            <li class="select2-selection__choice" title="Red">
                                                                                                <span aria-hidden="true" class="select2-selection__choice__remove" role="presentation">
                                                                                                    ×
                                                                                                </span>
                                                                                                Red
                                                                                            </li>
                                                                                            <li class="select2-search select2-search--inline">
                                                                                                <input aria-autocomplete="list" autocapitalize="none" autocomplete="off" autocorrect="off" class="select2-search__field" placeholder="" role="textbox" spellcheck="false" style="width: 0.75em;" tabindex="0" type="text"/>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </span>
                                                                                </span>
                                                                                <span aria-hidden="true" class="dropdown-wrapper">
                                                                                </span>
                                                                            </span>
                                                                            <button class="button plus select_all_attributes">
                                                                                Select all
                                                                            </button>
                                                                            <button class="button minus select_no_attributes">
                                                                                Select none
                                                                            </button>
                                                                            <button class="button fr plus add_new_attribute">
                                                                                Add new
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <label>
                                                                                <input checked="checked" class="checkbox" name="attribute_visibility[0]" type="checkbox" value="1">
                                                                                    Visible on the product page
                                                                                </input>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="enable_variation show_if_variable" style="display: block;">
                                                                                <label>
                                                                                    <input checked="checked" class="checkbox" name="attribute_variation[0]" type="checkbox" value="1">
                                                                                        Used for variations
                                                                                    </input>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="woocommerce_attribute wc-metabox closed " data-taxonomy="" rel="1">
                                                        <h3 class="">
                                                            <a class="remove_row delete" href="#">
                                                                Remove
                                                            </a>
                                                            <div aria-expanded="true" class="handlediv" title="Click to toggle">
                                                            </div>
                                                            <div class="tips sort">
                                                            </div>
                                                            <strong class="attribute_name">
                                                                Logo
                                                            </strong>
                                                        </h3>
                                                        <div class="woocommerce_attribute_data wc-metabox-content hidden" style="display: none;">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="attribute_name">
                                                                            <label>
                                                                                Name:
                                                                            </label>
                                                                            <input class="attribute_name" name="attribute_names[1]" type="text" value="Logo">
                                                                                <input class="attribute_position" name="attribute_position[1]" type="hidden" value="1">
                                                                                </input>
                                                                            </input>
                                                                        </td>
                                                                        <td rowspan="3">
                                                                            <label>
                                                                                Value(s):
                                                                            </label>
                                                                            <textarea cols="5" name="attribute_values[1]" placeholder='Enter some text, or some attributes by "|" separating values.' rows="5">
                                                                                Yes | No
                                                                            </textarea>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <label>
                                                                                <input checked="checked" class="checkbox" name="attribute_visibility[1]" type="checkbox" value="1">
                                                                                    Visible on the product page
                                                                                </input>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="enable_variation show_if_variable" style="display: block;">
                                                                                <label>
                                                                                    <input checked="checked" class="checkbox" name="attribute_variation[1]" type="checkbox" value="1">
                                                                                        Used for variations
                                                                                    </input>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="toolbar">
                                                    <span class="expand-close">
                                                        <a class="expand_all" href="#">
                                                            Expand
                                                        </a>
                                                        /
                                                        <a class="close_all" href="#">
                                                            Close
                                                        </a>
                                                    </span>
                                                    <button class="button save_attributes button-primary" type="button">
                                                        Save attributes
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wc-tab-panel panel woocommerce_options_panel hidden" id="advanced_product_data" style="display: none;">
                                                <div class="options_group hide_if_external hide_if_grouped">
                                                    <p class="form-field _purchase_note_field ">
                                                        <label for="_purchase_note">
                                                            Purchase note
                                                        </label>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                        <textarea class="short" cols="20" id="_purchase_note" name="_purchase_note" placeholder="" rows="2" style="">
                                                        </textarea>
                                                    </p>
                                                </div>
                                                <div class="options_group">
                                                    <p class="form-field menu_order_field ">
                                                        <label for="menu_order">
                                                            Menu order
                                                        </label>
                                                        <span class="woocommerce-help-tip">
                                                        </span>
                                                        <input class="short" id="menu_order" name="menu_order" placeholder="" step="1" style="" type="number" value="0">
                                                        </input>
                                                    </p>
                                                </div>
                                                <div class="options_group reviews">
                                                    <p class="form-field comment_status_field ">
                                                        <label for="comment_status">
                                                            Enable reviews
                                                        </label>
                                                        <input checked="checked" class="checkbox" id="comment_status" name="comment_status" style="" type="checkbox" value="open">
                                                        </input>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="wc-tab-panel panel wc-metaboxes-wrapper hidden" id="variable_product_options" style="display: none;">
                                                <div id="variable_product_options_inner">
                                                    <div class="toolbar toolbar-variations-defaults">
                                                        <div class="variations-defaults">
                                                            <strong>
                                                                Default Form Values:
                                                                <span class="woocommerce-help-tip">
                                                                </span>
                                                            </strong>
                                                            <select data-current="" name="default_attribute_pa_color">
                                                                <option value="">
                                                                    No default color…
                                                                </option>
                                                                <option value="blue">
                                                                    Blue
                                                                </option>
                                                                <option value="green">
                                                                    Green
                                                                </option>
                                                                <option value="red">
                                                                    Red
                                                                </option>
                                                            </select>
                                                            <select data-current="" name="default_attribute_logo">
                                                                <option value="">
                                                                    No default Logo…
                                                                </option>
                                                                <option value="Yes">
                                                                    Yes
                                                                </option>
                                                                <option value="No">
                                                                    No
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="clear">
                                                        </div>
                                                    </div>
                                                    <div class="toolbar toolbar-top">
                                                        <select class="variation_actions" id="field_to_edit">
                                                            <option data-global="true" value="add_variation">
                                                                Add variation
                                                            </option>
                                                            <option data-global="true" value="link_all_variations">
                                                                Create variations from all attributes
                                                            </option>
                                                            <option value="delete_all">
                                                                Delete all variations
                                                            </option>
                                                            <optgroup label="Status">
                                                                <option value="toggle_enabled">
                                                                    Toggle "Enabled"
                                                                </option>
                                                                <option value="toggle_downloadable">
                                                                    Toggle "Downloadable"
                                                                </option>
                                                                <option value="toggle_virtual">
                                                                    Toggle "Virtual"
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Pricing">
                                                                <option value="variable_regular_price">
                                                                    Set regular prices
                                                                </option>
                                                                <option value="variable_regular_price_increase">
                                                                    Increase regular prices (fixed amount or percentage)
                                                                </option>
                                                                <option value="variable_regular_price_decrease">
                                                                    Decrease regular prices (fixed amount or percentage)
                                                                </option>
                                                                <option value="variable_sale_price">
                                                                    Set sale prices
                                                                </option>
                                                                <option value="variable_sale_price_increase">
                                                                    Increase sale prices (fixed amount or percentage)
                                                                </option>
                                                                <option value="variable_sale_price_decrease">
                                                                    Decrease sale prices (fixed amount or percentage)
                                                                </option>
                                                                <option value="variable_sale_schedule">
                                                                    Set scheduled sale dates
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Inventory">
                                                                <option value="toggle_manage_stock">
                                                                    Toggle "Manage stock"
                                                                </option>
                                                                <option value="variable_stock">
                                                                    Stock
                                                                </option>
                                                                <option value="variable_stock_status_instock">
                                                                    Set Status - In stock
                                                                </option>
                                                                <option value="variable_stock_status_outofstock">
                                                                    Set Status - Out of stock
                                                                </option>
                                                                <option value="variable_stock_status_onbackorder">
                                                                    Set Status - On backorder
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Shipping">
                                                                <option value="variable_length">
                                                                    Length
                                                                </option>
                                                                <option value="variable_width">
                                                                    Width
                                                                </option>
                                                                <option value="variable_height">
                                                                    Height
                                                                </option>
                                                                <option value="variable_weight">
                                                                    Weight
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Downloadable products">
                                                                <option value="variable_download_limit">
                                                                    Download limit
                                                                </option>
                                                                <option value="variable_download_expiry">
                                                                    Download expiry
                                                                </option>
                                                            </optgroup>
                                                        </select>
                                                        <a class="button bulk_edit do_variation_action">
                                                            Go
                                                        </a>
                                                        <div class="variations-pagenav">
                                                            <span class="displaying-num">
                                                                0 items
                                                            </span>
                                                            <span class="expand-close">
                                                                (
                                                                <a class="expand_all" href="#">
                                                                    Expand
                                                                </a>
                                                                /
                                                                <a class="close_all" href="#">
                                                                    Close
                                                                </a>
                                                                )
                                                            </span>
                                                            <span class="pagination-links">
                                                                <a class="first-page disabled" href="#" title="Go to the first page">
                                                                    «
                                                                </a>
                                                                <a class="prev-page disabled" href="#" title="Go to the previous page">
                                                                    ‹
                                                                </a>
                                                                <span class="paging-select">
                                                                    <label class="screen-reader-text" for="current-page-selector-1">
                                                                        Select Page
                                                                    </label>
                                                                    <select class="page-selector" id="current-page-selector-1" title="Current page">
                                                                    </select>
                                                                    of
                                                                    <span class="total-pages">
                                                                        0
                                                                    </span>
                                                                </span>
                                                                <a class="next-page" href="#" title="Go to the next page">
                                                                    ›
                                                                </a>
                                                                <a class="last-page" href="#" title="Go to the last page">
                                                                    »
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="clear">
                                                        </div>
                                                    </div>
                                                    <div class="woocommerce_variations wc-metaboxes" data-attributes='{"pa_color":{"id":1,"name":"pa_color","options":[38,59,84],"position":0,"visible":true,"variation":true,"is_visible":1,"is_variation":1,"is_taxonomy":1,"value":""},"logo":{"id":0,"name":"Logo","options":["Yes","No"],"position":1,"visible":true,"variation":true,"is_visible":1,"is_variation":1,"is_taxonomy":0,"value":"Yes | No"}}' data-edited="false" data-page="1" data-total="0" data-total_pages="0">
                                                    </div>
                                                    <div class="toolbar">
                                                        <button class="button-primary save-variation-changes" disabled="disabled" type="button">
                                                            Save changes
                                                        </button>
                                                        <button class="button cancel-variation-changes" disabled="disabled" type="button">
                                                            Cancel
                                                        </button>
                                                        <div class="variations-pagenav">
                                                            <span class="displaying-num">
                                                                0 items
                                                            </span>
                                                            <span class="expand-close">
                                                                (
                                                                <a class="expand_all" href="#">
                                                                    Expand
                                                                </a>
                                                                /
                                                                <a class="close_all" href="#">
                                                                    Close
                                                                </a>
                                                                )
                                                            </span>
                                                            <span class="pagination-links">
                                                                <a class="first-page disabled" href="#" title="Go to the first page">
                                                                    «
                                                                </a>
                                                                <a class="prev-page disabled" href="#" title="Go to the previous page">
                                                                    ‹
                                                                </a>
                                                                <span class="paging-select">
                                                                    <label class="screen-reader-text" for="current-page-selector-1">
                                                                        Select Page
                                                                    </label>
                                                                    <select class="page-selector" id="current-page-selector-1" title="Current page">
                                                                    </select>
                                                                    of
                                                                    <span class="total-pages">
                                                                        0
                                                                    </span>
                                                                </span>
                                                                <a class="next-page" href="#" title="Go to the next page">
                                                                    ›
                                                                </a>
                                                                <a class="last-page" href="#" title="Go to the last page">
                                                                    »
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="clear">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="clear">
                                            </div>
                                        </div>
                                    </input>
                                </input>
                            </div>
                        </div>
                        <div class="postbox " id="product_data">
                            <button aria-expanded="true" class="handlediv" type="button">
                                <span class="screen-reader-text">
                                    Toggle panel: Listing Product Datas
                                </span>
                                <span aria-hidden="true" class="toggle-indicator">
                                </span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>
                                    Listing Product Datas
                                </span>
                            </h2>
                            <div class="inside">
                                <input id="_cth_cpt_nonce" name="_cth_cpt_nonce" type="hidden" value="4d5ee58811">
                                    <input name="_wp_http_referer" type="hidden" value="/townhub/wp-admin/post.php?post=45&action=edit">
                                        <div id="react-woo-app">
                                        </div>
                                    </input>
                                </input>
                            </div>
                        </div>
                        <div class="postbox hide-if-js" id="postcustom" style="">
                            <button aria-expanded="true" class="handlediv" type="button">
                                <span class="screen-reader-text">
                                    Toggle panel: Custom Fields
                                </span>
                                <span aria-hidden="true" class="toggle-indicator">
                                </span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>
                                    Custom Fields
                                </span>
                            </h2>
                            <div class="inside">
                                <div id="postcustomstuff">
                                    <div id="ajax-response">
                                    </div>
                                    <table id="list-table">
                                        <thead>
                                            <tr>
                                                <th class="left">
                                                    Name
                                                </th>
                                                <th>
                                                    Value
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody data-wp-lists="list:meta" id="the-list">
                                            <tr id="meta-4606">
                                                <td class="left">
                                                    <label class="screen-reader-text" for="meta-4606-key">
                                                        Key
                                                    </label>
                                                    <input id="meta-4606-key" name="meta[4606][key]" size="20" type="text" value="total_sales">
                                                        <div class="submit">
                                                            <input class="button deletemeta button-small" data-wp-lists="delete:the-list:meta-4606::_ajax_nonce=f4089b82c2" id="deletemeta[4606]" name="deletemeta[4606]" type="submit" value="Delete">
                                                                <input class="button updatemeta button-small" data-wp-lists="add:the-list:meta-4606::_ajax_nonce-add-meta=1f33d8f24a" id="meta-4606-submit" name="meta-4606-submit" type="submit" value="Update"/>
                                                            </input>
                                                        </div>
                                                        <input id="_ajax_nonce" name="_ajax_nonce" type="hidden" value="ebfd1a7291"/>
                                                    </input>
                                                </td>
                                                <td>
                                                    <label class="screen-reader-text" for="meta-4606-value">
                                                        Value
                                                    </label>
                                                    <textarea cols="30" id="meta-4606-value" name="meta[4606][value]" rows="2">
                                                        1
                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p>
                                        <strong>
                                            Add New Custom Field:
                                        </strong>
                                    </p>
                                    <table id="newmeta">
                                        <thead>
                                            <tr>
                                                <th class="left">
                                                    <label for="metakeyselect">
                                                        Name
                                                    </label>
                                                </th>
                                                <th>
                                                    <label for="metavalue">
                                                        Value
                                                    </label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="left" id="newmetaleft">
                                                    <select id="metakeyselect" name="metakeyselect">
                                                        <option value="#NONE#">
                                                            — Select —
                                                        </option>
                                                        <option value="0_key">
                                                            0_key
                                                        </option>
                                                        <option value="Cleanliness_key">
                                                            Cleanliness_key
                                                        </option>
                                                        <option value="Comfort_key">
                                                            Comfort_key
                                                        </option>
                                                        <option value="cus_field_2lyl0je78_key">
                                                            cus_field_2lyl0je78_key
                                                        </option>
                                                        <option value="Facilities_key">
                                                            Facilities_key
                                                        </option>
                                                        <option value="is_vat_exempt">
                                                            is_vat_exempt
                                                        </option>
                                                        <option value="NAN_key">
                                                            NAN_key
                                                        </option>
                                                        <option value="product_image_gallery">
                                                            product_image_gallery
                                                        </option>
                                                        <option value="resmushed_cumulated_optimized_sizes">
                                                            resmushed_cumulated_optimized_sizes
                                                        </option>
                                                        <option value="resmushed_cumulated_original_sizes">
                                                            resmushed_cumulated_original_sizes
                                                        </option>
                                                        <option value="resmushed_quality">
                                                            resmushed_quality
                                                        </option>
                                                        <option value="room_images">
                                                            room_images
                                                        </option>
                                                        <option value="siteground_optimizer_is_optimized">
                                                            siteground_optimizer_is_optimized
                                                        </option>
                                                        <option value="siteground_optimizer_optimization_attempts">
                                                            siteground_optimizer_optimization_attempts
                                                        </option>
                                                        <option value="siteground_optimizer_optimization_failed">
                                                            siteground_optimizer_optimization_failed
                                                        </option>
                                                        <option value="slide_template">
                                                            slide_template
                                                        </option>
                                                        <option value="Staf_key">
                                                            Staf_key
                                                        </option>
                                                        <option value="total_sales">
                                                            total_sales
                                                        </option>
                                                    </select>
                                                    <input class="hide-if-js" id="metakeyinput" name="metakeyinput" type="text" value="">
                                                        <a class="hide-if-no-js" href="#postcustomstuff" onclick="jQuery('#metakeyinput, #metakeyselect, #enternew, #cancelnew').toggle();return false;">
                                                            <span id="enternew">
                                                                Enter new
                                                            </span>
                                                            <span class="hidden" id="cancelnew">
                                                                Cancel
                                                            </span>
                                                        </a>
                                                    </input>
                                                </td>
                                                <td>
                                                    <textarea cols="25" id="metavalue" name="metavalue" rows="2">
                                                    </textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="submit">
                                                        <input class="button" data-wp-lists="add:the-list:newmeta" id="newmeta-submit" name="addmeta" type="submit" value="Add Custom Field"/>
                                                    </div>
                                                    <input id="_ajax_nonce-add-meta" name="_ajax_nonce-add-meta" type="hidden" value="1f33d8f24a"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p>
                                    Custom fields can be used to add extra metadata to a post that you can
                                    <a href="https://wordpress.org/support/article/custom-fields/">
                                        use in your theme
                                    </a>
                                    .
                                </p>
                            </div>
                        </div>
                        <div class="postbox hide-if-js" id="slugdiv" style="">
                            <button aria-expanded="true" class="handlediv" type="button">
                                <span class="screen-reader-text">
                                    Toggle panel: Slug
                                </span>
                                <span aria-hidden="true" class="toggle-indicator">
                                </span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>
                                    Slug
                                </span>
                            </h2>
                            <div class="inside">
                                <label class="screen-reader-text" for="post_name">
                                    Slug
                                </label>
                                <input id="post_name" name="post_name" size="13" type="text" value="hoodie">
                                </input>
                            </div>
                        </div>
                        <div class="postbox " id="postexcerpt">
                            <button aria-expanded="true" class="handlediv" type="button">
                                <span class="screen-reader-text">
                                    Toggle panel: Product short description
                                </span>
                                <span aria-hidden="true" class="toggle-indicator">
                                </span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>
                                    Product short description
                                </span>
                            </h2>
                            <div class="inside">
                                <div class="wp-core-ui wp-editor-wrap tmce-active" id="wp-excerpt-wrap">
                                    <style>
                                        #wp-excerpt-editor-container .wp-editor-area{height:175px; width:100%;}
                                    </style>
                                    <div class="wp-editor-tools hide-if-no-js" id="wp-excerpt-editor-tools">
                                        <div class="wp-media-buttons" id="wp-excerpt-media-buttons">
                                            <button class="button insert-media add_media" data-editor="excerpt" type="button">
                                                <span class="wp-media-buttons-icon">
                                                </span>
                                                Add Media
                                            </button>
                                        </div>
                                        <div class="wp-editor-tabs">
                                            <button class="wp-switch-editor switch-tmce" data-wp-editor-id="excerpt" id="excerpt-tmce" type="button">
                                                Visual
                                            </button>
                                            <button class="wp-switch-editor switch-html" data-wp-editor-id="excerpt" id="excerpt-html" type="button">
                                                Text
                                            </button>
                                        </div>
                                    </div>
                                    <div class="wp-editor-container" id="wp-excerpt-editor-container">
                                        <div class="quicktags-toolbar" id="qt_excerpt_toolbar">
                                            <input aria-label="Bold" class="ed_button button button-small" id="qt_excerpt_strong" type="button" value="b">
                                                <input aria-label="Italic" class="ed_button button button-small" id="qt_excerpt_em" type="button" value="i">
                                                    <input aria-label="Insert link" class="ed_button button button-small" id="qt_excerpt_link" type="button" value="link"/>
                                                </input>
                                            </input>
                                        </div>
                                        <div class="mce-tinymce mce-container mce-panel" hidefocus="1" id="mceu_84" role="application" style="visibility: hidden; border-width: 1px; width: 100%;" tabindex="-1">
                                            <div class="mce-container-body mce-stack-layout" id="mceu_84-body">
                                                <div class="mce-top-part mce-container mce-stack-layout-item mce-first" id="mceu_85">
                                                    <div class="mce-container-body" id="mceu_85-body">
                                                        <div class="mce-toolbar-grp mce-container mce-panel mce-first mce-last" hidefocus="1" id="mceu_86" role="group" tabindex="-1">
                                                            <div class="mce-container-body mce-stack-layout" id="mceu_86-body">
                                                                <div class="mce-container mce-toolbar mce-stack-layout-item mce-first" id="mceu_87" role="toolbar">
                                                                    <div class="mce-container-body mce-flow-layout" id="mceu_87-body">
                                                                        <div class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" id="mceu_88" role="group">
                                                                            <div id="mceu_88-body">
                                                                                <div aria-haspopup="true" aria-labelledby="mceu_60" class="mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox mce-first mce-btn-has-text" id="mceu_60" role="button" tabindex="-1">
                                                                                    <button id="mceu_60-open" role="presentation" tabindex="-1" type="button">
                                                                                        <span class="mce-txt">
                                                                                            Paragraph
                                                                                        </span>
                                                                                        <i class="mce-caret">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Bold (⌘B)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_61" role="button" tabindex="-1">
                                                                                    <button id="mceu_61-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-bold">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Italic (⌘I)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_62" role="button" tabindex="-1">
                                                                                    <button id="mceu_62-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-italic">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Bulleted list (⌃⌥U)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_63" role="button" tabindex="-1">
                                                                                    <button id="mceu_63-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-bullist">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Numbered list (⌃⌥O)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_64" role="button" tabindex="-1">
                                                                                    <button id="mceu_64-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-numlist">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Blockquote (⌃⌥Q)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_65" role="button" tabindex="-1">
                                                                                    <button id="mceu_65-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-blockquote">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Align left (⌃⌥L)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_66" role="button" tabindex="-1">
                                                                                    <button id="mceu_66-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-alignleft">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Align center (⌃⌥C)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_67" role="button" tabindex="-1">
                                                                                    <button id="mceu_67-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-aligncenter">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Align right (⌃⌥R)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_68" role="button" tabindex="-1">
                                                                                    <button id="mceu_68-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-alignright">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Insert/edit link (⌘K)" aria-pressed="false" class="mce-widget mce-btn" id="mceu_69" role="button" tabindex="-1">
                                                                                    <button id="mceu_69-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-link">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Insert Read More tag (⌃⌥T)" class="mce-widget mce-btn" id="mceu_70" role="button" tabindex="-1">
                                                                                    <button id="mceu_70-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-wp_more">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Fullscreen" aria-pressed="false" class="mce-widget mce-btn" id="mceu_71" role="button" tabindex="-1">
                                                                                    <button id="mceu_71-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-fullscreen">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Toolbar Toggle (⌃⌥Z)" class="mce-widget mce-btn mce-last" id="mceu_72" role="button" tabindex="-1">
                                                                                    <button id="mceu_72-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-wp_adv">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mce-container mce-toolbar mce-stack-layout-item mce-last" id="mceu_89" role="toolbar" style="display: none;">
                                                                    <div class="mce-container-body mce-flow-layout" id="mceu_89-body">
                                                                        <div class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" id="mceu_90" role="group">
                                                                            <div id="mceu_90-body">
                                                                                <div aria-label="Strikethrough (⌃⌥D)" aria-pressed="false" class="mce-widget mce-btn mce-first" id="mceu_73" role="button" tabindex="-1">
                                                                                    <button id="mceu_73-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-strikethrough">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Horizontal line" class="mce-widget mce-btn" id="mceu_74" role="button" tabindex="-1">
                                                                                    <button id="mceu_74-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-hr">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-haspopup="true" aria-label="Text color" class="mce-widget mce-btn mce-splitbtn mce-colorbutton" id="mceu_75" role="button" tabindex="-1">
                                                                                    <button hidefocus="1" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-forecolor">
                                                                                        </i>
                                                                                        <span class="mce-preview" id="mceu_75-preview">
                                                                                        </span>
                                                                                    </button>
                                                                                    <button class="mce-open" hidefocus="1" tabindex="-1" type="button">
                                                                                        <i class="mce-caret">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Paste as text" aria-pressed="false" class="mce-widget mce-btn" id="mceu_76" role="button" tabindex="-1">
                                                                                    <button id="mceu_76-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-pastetext">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Clear formatting" class="mce-widget mce-btn" id="mceu_77" role="button" tabindex="-1">
                                                                                    <button id="mceu_77-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-removeformat">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Special character" class="mce-widget mce-btn" id="mceu_78" role="button" tabindex="-1">
                                                                                    <button id="mceu_78-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-charmap">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Decrease indent" class="mce-widget mce-btn" id="mceu_79" role="button" tabindex="-1">
                                                                                    <button id="mceu_79-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-outdent">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Increase indent" class="mce-widget mce-btn" id="mceu_80" role="button" tabindex="-1">
                                                                                    <button id="mceu_80-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-indent">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-disabled="true" aria-label="Undo (⌘Z)" class="mce-widget mce-btn mce-disabled" id="mceu_81" role="button" tabindex="-1">
                                                                                    <button id="mceu_81-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-undo">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-disabled="true" aria-label="Redo (⌘Y)" class="mce-widget mce-btn mce-disabled" id="mceu_82" role="button" tabindex="-1">
                                                                                    <button id="mceu_82-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-redo">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                                <div aria-label="Keyboard Shortcuts (⌃⌥H)" class="mce-widget mce-btn mce-last" id="mceu_83" role="button" tabindex="-1">
                                                                                    <button id="mceu_83-button" role="presentation" tabindex="-1" type="button">
                                                                                        <i class="mce-ico mce-i-wp_help">
                                                                                        </i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mce-edit-area mce-container mce-panel mce-stack-layout-item" hidefocus="1" id="mceu_91" role="group" style="border-width: 1px 0px 0px;" tabindex="-1">
                                                    <iframe allowtransparency="true" frameborder="0" id="excerpt_ifr" style="width: 100%; height: 209px; display: block;" title="Rich Text Area. Press Control-Option-H for help.">
                                                    </iframe>
                                                </div>
                                                <div class="mce-statusbar mce-container mce-panel mce-stack-layout-item mce-last" hidefocus="1" id="mceu_92" role="group" style="border-width: 1px 0px 0px;" tabindex="-1">
                                                    <div class="mce-container-body mce-flow-layout" id="mceu_92-body">
                                                        <div class="mce-path mce-flow-layout-item mce-first" id="mceu_93">
                                                            <div class="mce-path-item">
                                                            </div>
                                                        </div>
                                                        <div class="mce-flow-layout-item mce-last mce-resizehandle" id="mceu_94">
                                                            <i class="mce-ico mce-i-resize">
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea aria-hidden="true" autocomplete="off" class="wp-editor-area" cols="40" id="excerpt" name="excerpt" rows="20" style="display: none;">
                                            <p>Praesent eros turpis, commodo vel justo at, pulvinar mollis eros. Mauris aliquet eu quam id ornare. Morbi ac quam enim. Cras vitae nulla condimentum, semper dolor non, faucibus dolor. Vivamus adipiscing eros quis orci fringilla, sed pretium lectus viverra.</p>
                                        </textarea>
                                    </div>
                                    <div class="uploader-editor">
                                        <div class="uploader-editor-content">
                                            <div class="uploader-editor-title">
                                                Drop files to upload
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="postbox " id="commentsdiv">
                            <button aria-expanded="true" class="handlediv" type="button">
                                <span class="screen-reader-text">
                                    Toggle panel: Reviews
                                </span>
                                <span aria-hidden="true" class="toggle-indicator">
                                </span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>
                                    Reviews
                                </span>
                            </h2>
                            <div class="inside">
                                <input id="add_comment_nonce" name="add_comment_nonce" type="hidden" value="ba66ab3339">
                                    <p class="hide-if-no-js" id="add-new-comment">
                                        <button class="button" onclick="window.commentReply && commentReply.addcomment(45);" type="button">
                                            Add Comment
                                        </button>
                                    </p>
                                    <input id="_ajax_fetch_list_nonce" name="_ajax_fetch_list_nonce" type="hidden" value="1b9bf8868d">
                                        <input name="_wp_http_referer" type="hidden" value="/townhub/wp-admin/post.php?post=45&action=edit">
                                            <table class="widefat fixed striped comments wp-list-table comments-box" style="display:none;">
                                                <tbody data-wp-lists="list:comment" id="the-comment-list">
                                                </tbody>
                                            </table>
                                            <p id="no-comments">
                                                No comments yet.
                                            </p>
                                            <div class="hidden" id="trash-undo-holder">
                                                <div class="trash-undo-inside">
                                                    Comment by
                                                    <strong>
                                                    </strong>
                                                    moved to the Trash.
                                                    <span class="undo untrash">
                                                        <a href="#">
                                                            Undo
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="hidden" id="spam-undo-holder">
                                                <div class="spam-undo-inside">
                                                    Comment by
                                                    <strong>
                                                    </strong>
                                                    marked as spam.
                                                    <span class="undo unspam">
                                                        <a href="#">
                                                            Undo
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </input>
                                    </input>
                                </input>
                            </div>
                        </div>
                    </div>
                    <div class="meta-box-sortables ui-sortable" id="advanced-sortables">
                    </div>
                </div>

                </div>
                
            </div><!-- end dashboard-list-box -->
        </div><!-- end dashboard-content-wrapper -->
