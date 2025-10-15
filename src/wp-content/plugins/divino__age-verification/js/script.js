jQuery(document).ready(function($) {
    var modal = $("#age-verification-modal");
    var verifyBtn = $("#age-verify-btn");

    // Проверяем куку при загрузке
    if (!getCookie(ageVerificationData.cookieName)) {
        // Небольшая задержка для плавного появления
        setTimeout(function() {
            modal.addClass("show");
            $("body").css("overflow", "hidden");
        }, 500);
    }

    // Обработчик клика по кнопке
    verifyBtn.on("click", function() {
        // Устанавливаем куку
        setCookie(
            ageVerificationData.cookieName,
            "verified",
            ageVerificationData.cookieDuration
        );

        // Скрываем модальное окно
        modal.removeClass("show");
        $("body").css("overflow", "");

        setTimeout(function() {
            modal.hide();
        }, 300);
    });

    // Функция установки куки
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
    }

    // Функция получения куки
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(";");
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == " ") c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});
