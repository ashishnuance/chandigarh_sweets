<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Web Layout</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ mix('css/style.css')}}">
  <link rel="stylesheet" href="{{ mix('css/master.css')}}">
  <link rel="stylesheet" href="{{ mix('css/responsive.css')}}">
</head>

<body>
    <div id="app">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center py-4 sm:pt-0">
            <welcome/>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper(".FirstSwiper", {
      slidesPerView: 4,
      spaceBetween: 30,
      navigation: {
        nextEl: ".FirstNext",
        prevEl: ".FirstPrev",
      },
      breakpoints: {

        320: {
          slidesPerView: 1,
        },

        480: {
          slidesPerView: 1,
        },

        768: {
          slidesPerView: 2,
        },

        1024: {
          slidesPerView: 4,
        }
      }
    });
  </script>
  <script>
    var swiper = new Swiper(".SecondSwiper", {
      slidesPerView: 4,
      spaceBetween: 30,
      navigation: {
        nextEl: ".SecondNext ",
        prevEl: ".SecondPrev",
      },
      breakpoints: {

        320: {
          slidesPerView: 1,
        },

        480: {
          slidesPerView: 1,
        },

        768: {
          slidesPerView: 2,
        },

        1024: {
          slidesPerView: 4,
        }
      }
    });
  </script>
</body>

</html>