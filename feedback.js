let selectedRating = 0;
const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating-value');

stars.forEach(star => {
  star.addEventListener('click', () => {
    selectedRating = parseInt(star.getAttribute('data-star'));
    ratingInput.value = selectedRating;
    updateStars();
  });
});

function updateStars() {
  stars.forEach(star => {
    const starValue = parseInt(star.getAttribute('data-star'));
    if (starValue <= selectedRating) {
      star.classList.add('selected');
    } else {
      star.classList.remove('selected');
    }
  });
}

document.getElementById('image-upload').addEventListener('change', function () {
  const preview = document.getElementById('preview');
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }
});
