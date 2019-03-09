jQuery(function($){
  /*
   * Select/Upload image(s) event
   */
  jQuery(document).ready( function($){ 

    $('.vrr-custom_upload_image_button').on('click', function(e){
      e.preventDefault();
   
      var button = $(this);
      var custom_uploader = wp.media({
        title: 'Insert image',
        library : {
          // uncomment the next line if you want to attach image to the current post
          // uploadedTo : wp.media.view.settings.post.id, 
          type : 'image'
        },
        button: {
          text: 'Use this image' // button label text
        },
        multiple: false // for multiple image selection set to true
      }).on('select', function() { // it also has "open" and "close" events 
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        var wrap = $(button).closest('.vrr-background-field-wrap');
        wrap.find('.vrr-background-image-holder-wrap').css('background-image', 'url('+ attachment.url +')');
        wrap.find('input[type="hidden"]').val(attachment.url);
        wrap.find('.vrr-custom_remove_image_button').show();
        if(wrap.attr('data-background') == 'background-canvas'){
          $('.vrr-draggable').css('background-image', 'url('+ attachment.url +')');
        }
        if(wrap.attr('data-background') == 'background-table'){
          $('.vrr-element .vrr-element-table').css('background-image', 'url('+ attachment.url +')');
        }
        if(wrap.attr('data-background') == 'background-seat'){
          $('.vrr-element .vrr-element-seats-wrap .vrr-element-seat').css('background-image', 'url('+ attachment.url +')');
        }
        /* if you sen multiple to true, here is some code for getting the image IDs
        var attachments = frame.state().get('selection'),
            attachment_ids = new Array(),
            i = 0;
        attachments.each(function(attachment) {
          attachment_ids[i] = attachment['id'];
          console.log( attachment );
          i++;
        });
        */
      })
      .open();
    });

    /*
     * Remove image event
     */
    $('.vrr-custom_remove_image_button').on('click', function(){
      var wrap = $(this).closest('.vrr-background-field-wrap');
      wrap.find('.vrr-background-image-holder-wrap').css('background-image', '');
      wrap.find('input[type="hidden"]').val('');
      wrap.find('.vrr-custom_remove_image_button').show();
      if(wrap.attr('data-background') == 'background-canvas'){
        wrap.find('.vrr-background-image-holder-wrap').css('background-image', '');
        $('.vrr-draggable').css('background-image', '');
        $('.vrr-draggable').addClass('no-bg');
      }
      if(wrap.attr('data-background') == 'background-table'){
        $('.vrr-element .vrr-element-seats-wrap .vrr-element-table').css('background-image', '');
        $('.vrr-element').addClass('no-bg-table');
      }
      if(wrap.attr('data-background') == 'background-seat'){
        $('.vrr-element .vrr-element-seats-wrap .vrr-element-seat').css('background-image', '');
        $('.vrr-element').addClass('no-bg-seats');
      }

      return false;
    });

  });
 
});