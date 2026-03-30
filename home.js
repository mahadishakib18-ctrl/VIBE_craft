 const images = document.querySelectorAll('.carousel-image');
    let current = 0;

    setInterval(() => {
      images[current].classList.remove('active');
      current = (current + 1) % images.length;
      images[current].classList.add('active');
    }, 3000);

      // Slideshow Logic
    let slideIndex = 0;
    const slides = document.querySelectorAll('.carousel-image');

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) slide.classList.add('active');
      });
    }

    function nextSlide() {
      slideIndex = (slideIndex + 1) % slides.length;
      showSlide(slideIndex);
    }

    function prevSlide() {
      slideIndex = (slideIndex - 1 + slides.length) % slides.length;
      showSlide(slideIndex);
    }

    // Auto Slide
    setInterval(() => {
      nextSlide();
    }, 4000);

    // Initial Display
    document.addEventListener('DOMContentLoaded', () => {
      showSlide(slideIndex);
    });