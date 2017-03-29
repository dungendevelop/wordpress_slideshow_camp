jQuery(document).ready(function(){
  jQuery(".campslider").each(function(){
      var $this = jQuery(this);
      var args = {
        dots: true,
        infinite: true,
        arrows:true,
        speed: 300,
        autoplay:false,
        autoplaySpeed: 2000,
        slidesToShow: 1,
        adaptiveHeight: true
      };
      args.dots = ($this.data('dots')?true:false); 
      args.autoplay=($this.data('autoplay')?true:false);
      args.arrows = ($this.data('arrows')?true:false);
      args.autoplaySpeed = ($this.data('autoplay_interval')?$this.data('autoplay_interval'):0);
      $this.slick(args);
  });
});