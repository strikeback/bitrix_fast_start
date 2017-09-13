$(document).ready(function () {
    $('.js-slider-main').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        arrows: false,
        fade: true,
    });
    $(".js-scrollbar").mCustomScrollbar();
    $('[name=PHONE]').mask("+7(999)-999-99-99");

    $("body").on("submit", ".js-form-submit", function (e) {
        e.preventDefault();
        var _form = $(this);
        if (!myValidateForm(_form)) {
            return false;
        }
        var _data = _form.serializeObject();
        SendAjax("SEND_FORM", _data, function (data) {
            _form.html(data.html);
//            sendYandexGoal(data["GOAL"]);
        });
    });
    $("body").on("blur", ".js-form-submit input", function () {
        var _form = $(this).closest(".js-form-submit");
        if (!myValidateForm(_form)) {
            return false;
        }
    });
});