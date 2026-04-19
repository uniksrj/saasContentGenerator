import $ from 'jquery';

const slide = document.querySelector('.carousel-slide') ? document.querySelector('.carousel-slide') : null;
const caption = slide ? slide.querySelector('.carousel-caption') : null;

function showSlide(index) {
    if (!slide || !caption) {
        return;
    }
    slide.querySelector('img').setAttribute('src', slides[index].image);
    caption.querySelector('h3').textContent = slides[index].title;
    caption.querySelector('p').textContent = slides[index].text;
}

let currentSlide = 0;
const slides = [{
    image: "/assets/snow.webp",
    title: "SateLite View in sunrise",
    text: "Tech is best because it shows us the world in a new way."
},
{
    image: "/assets/green.webp",
    title: "Sun Makes You Feel Great",
    text: "Jobs fill your pocket, but adventures fill your soul."
},
{
    image: "/assets/beach.webp",
    title: "Peace Finds You",
    text: "Twenty years from now you will be more disappointed by the things that you didn't do than by the ones you did do."
}
];


$(document).ready(function () {
    if (slide && caption) {
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    }

    $('.loader-overlay').fadeIn(100);

    setTimeout(function () {
        $('.loader-overlay').fadeOut(300);
    }, 800);
    $(window).on('beforeunload', function () {
        $('.loader-overlay').show();
    });

    $(window).on('load', function () {
        setTimeout(function () {
            $('.loader-overlay').fadeOut(250);
        }, 50);
    });

    $('#loadMore').click(function () {
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

        setTimeout(() => {
            $(this).hide();
        }, 1000);
    });

    $(document).on('click', '#dropdown-cat', function (e) {
        console.log(window.innerWidth);
        if (window.innerWidth <= 768) {
            e.preventDefault();
            $(this).next('.dropdown-menu_category').slideToggle(300);
            $(this).toggleClass("show");
        }
    });
});
