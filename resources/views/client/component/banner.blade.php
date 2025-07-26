<div class="w-full bg-blue-100 py-12">
<div class="w-full">
  <div class="swiper swiper-banner w-full h-[600px] relative rounded-none overflow-hidden">
    <div class="swiper-wrapper">
      @for ($i = 1; $i <= 8; $i++)
        <div class="swiper-slide">
          <img src="{{ asset('images/Banner/Banner' . $i . '.jpg') }}" alt="Banner {{ $i }}"
            class="w-full h-full object-cover" />
        </div>
      @endfor
    </div>

    <!-- Navigation + pagination -->
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
    <div class="swiper-pagination"></div>
  </div>
</div>

  </div>

<script>
  const swiper = new Swiper('.swiper-banner', {
    slidesPerView: 1,
    loop: true,
    spaceBetween: 30,
    effect: 'fade',
    fadeEffect: { crossFade: true },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    speed: 800,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
</script>