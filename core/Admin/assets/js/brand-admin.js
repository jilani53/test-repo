jQuery(document).ready(function($) {

    //
    // Layout preset scripts.
    //
      var select_value_layout = $(".csf-field-layout_preset.sp_brand_layout_preset .csf--sibling.csf--image.csf--active").find("input").val();
      var select_image_layout = $(".csf-field-layout_preset.sp_brand_layout_preset .csf--sibling.csf--image");

      if ("carousel_layout" === select_value_layout) {
        $(".smart-brand-metabox-tabs .csf-theme-light .csf-nav ul li a.sp_smart_brand_metaboxes_2").show();
      } else {
        $(".smart-brand-metabox-tabs .csf-theme-light .csf-nav ul li a.sp_smart_brand_metaboxes_2").hide();
      }
  
      $(select_image_layout).click(function () {
        var select_value = $(this).find("input").val();

        if ("carousel_layout" != select_value) {
          $(".smart-brand-metabox-tabs .csf-theme-light .csf-nav ul li a.sp_smart_brand_metaboxes_2").hide();
        } else {
          $(".smart-brand-metabox-tabs .csf-theme-light .csf-nav ul li a.sp_smart_brand_metaboxes_2").show();
        }
      });

      //
      // Shortcode copy animation
      //
      $('.sp-smart-brand-scode-wrapper .sbfw-shcode-selectable').click(function (e) {
        e.preventDefault();
        sp_brand_copyToClipboard($(this));
        sp_brand_SelectText($(this));
        $(this).focus().select();
        jQuery(".sbfw-after-copy-text").animate({
          opacity: 1,
          bottom: 25
        }, 300);
        setTimeout(function () {
          jQuery(".sbfw-after-copy-text").animate({
            opacity: 0,
          }, 200);
          jQuery(".sbfw-after-copy-text").animate({
            bottom: 0
          }, 0);
        }, 2000);
      });

    $('.post-type-smart_brand_sc .column-shortcode input').click(function (e) {
    e.preventDefault();
    /* Get the text field */
    var copyText = $(this);
    /* Select the text field */
    copyText.select();
    document.execCommand("copy");
    jQuery(".sbfw-after-copy-text").animate({
      opacity: 1,
      bottom: 25
    }, 300);
    setTimeout(function () {
      jQuery(".sbfw-after-copy-text").animate({
        opacity: 0,
      }, 200);
      jQuery(".sbfw-after-copy-text").animate({
        bottom: 0
      }, 0);
    }, 2000);
  });
  function sp_brand_copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }
  function sp_brand_SelectText(element) {
      var r = document.createRange();
      var w = element.get(0);
      r.selectNodeContents(w);
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(r);
    }
});