const swiperElement = document.querySelector('.swiper');

document.addEventListener('DOMContentLoaded', function () {
    var swiperOptions = JSON.parse(swiperElement.getAttribute('data-swiper-options'));

    console.log(swiperOptions);
    
    var swiper = new Swiper('.swiper', swiperOptions);
});