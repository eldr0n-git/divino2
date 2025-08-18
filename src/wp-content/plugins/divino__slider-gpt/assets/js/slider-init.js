document.addEventListener('DOMContentLoaded', function(){
  var sliders = document.querySelectorAll('.divino-swiper');
  if (!sliders.length) return;
  sliders.forEach(function(el){
    new Swiper(el, {
      loop: true,
      autoplay: {
        delay: 4000, // default; per-slide overrides via data-swiper-autoplay
        disableOnInteraction: false
      },
      pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev')
      },
      slidesPerView: 1,
      effect: 'slide'
    });
  });
});
