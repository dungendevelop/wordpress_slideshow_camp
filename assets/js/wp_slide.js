jQuery(document).ready(function($) {


  $('.add_image').click(function(){
    var cloned =  $('.tobecloned:first').clone();
    cloned.addClass('cloned');
    $('#camp_slider_images_box').append(cloned);
  });

  $( "#camp_slider_images_box" ).sortable({
    handle: '.handle' , 
    axis: 'y',
    containment: '#camp_slider_images_box'
  });


  // Uploading files
  var file_frame;

  $.fn.upload_listing_image = function( button ) {

      // Create the media frame.
      file_frame = wp.media.frames.file_frame = wp.media({
        title: $( this ).data( 'uploader_title' ),
        button: {
          text: $( this ).data( 'uploader_button_text' ),
        },
        multiple: false
      });

      // When an image is selected, run a callback.
      file_frame.on( 'select', function() {
        var attachment = file_frame.state().get('selection').first().toJSON();
        console.log(attachment.id);
        button.parent().parent().find(".upload_listing_image").val(attachment.id);
        button.parent().parent().find("img").attr('src',attachment.url);
        button.parent().parent().find( 'img' ).show();
        button.removeClass( 'upload_listing_image_button' );
        button.addClass( 'remove_listing_image_button' );
        button.parent().parent().find( '.remove_listing_image_button' ).text( 'Remove listing image' );
      });

      // Finally, open the modal
      file_frame.open();
  };

  $('#camp_slider_images_box').on( 'click', '.upload_listing_image_button', function( event ) {
      var $this = $(this);
      event.preventDefault();

      $.fn.upload_listing_image( $this );
  });

  $('#camp_slider_images_box').on( 'click', '.remove_listing_image_button', function( event ) {
      event.preventDefault();
      var $this =  $(this);
      $this.parent().parent().find( '.upload_listing_image' ).val( '' );
      $this.parent().parent().find( 'img' ).attr( 'src', '' );
      $this.parent().parent().find( 'img' ).hide();
      $( this ).removeClass(  'remove_listing_image_button' );
      $( this ).addClass(  'upload_listing_image_button' );
      $this.parent().parent().find( '.upload_listing_image_button' ).text( 'Set listing image' );
  });

  //remove slide
  $('#camp_slider_images_box').on( 'click', '.remove_slide', function( event ) {
      var $this = $(this);
      $this.parent().remove();
  });
});