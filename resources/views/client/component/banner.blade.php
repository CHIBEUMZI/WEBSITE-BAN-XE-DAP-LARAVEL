<style>
  /* Header swiper chiếm toàn bộ chiều cao màn hình */
  .swiper-banner {
    height: 70vh;
  }

  /* .swiper-banner .swiper-wrapper,
  .swiper-banner .swiper-slide {
    height: 100%;
  } */

.swiper-banner .swiper-slide {
  display: flex;
  justify-content: center;
  align-items: center;
  /* background-color: #000; hoặc màu khác để ảnh nổi bật hơn */
}

.swiper-banner .swiper-slide img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}


</style>

<div class="relative w-full swiper-banner">
  <div class="max-w-7xl mx-auto h-full">
    <div class="swiper h-full">
      <div class="swiper-wrapper">
        @for ($i = 1; $i <= 5; $i++)
          <div class="swiper-slide">
            <img src="{{ asset('images/Banner/Banner' . $i . '.jpg') }}" alt="Banner xe đạp {{ $i }}">
          </div>
        @endfor
      </div>

      <!-- Nút điều hướng -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
  const swiper = new Swiper('.swiper', {
    slidesPerView: 1,
    spaceBetween: 30,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    loop: true,
    autoplay: {
      delay: 1000,
      disableOnInteraction: false, // tiếp tục autoplay sau khi người dùng tương tác
    },
    speed: 800,
    effect: 'fade',
    fadeEffect: { crossFade: true },
  });
</script>
