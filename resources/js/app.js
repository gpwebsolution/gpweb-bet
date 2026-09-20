import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const topNav = document.querySelector('.page__content__navbar');
    if (topNav) {
        window.addEventListener('scroll', function () {
            topNav.classList.toggle('navbar-scrolled', window.scrollY > 10);
        });
    }
});
