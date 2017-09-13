function sendYandexGoal(_target) {
    yaCounter37696905.reachGoal(_target);
    ga('send', 'pageview', '/' + _target + '.html');
}
function myValidateForm(form) {
    var _items = form.find(".req");
    form.find(".req").removeClass("error");
    var _valid = true;
    form.find('.req').each(function (index, el) { /*проверка заполнения*/
        var _input = $(el);
        if (_input.val() == "") {
            $(el).addClass('error');
            _valid = false;
        }
        if (_input.attr("type") == "checkbox" && _input.prop("checked") == false) {
            $(el).addClass('error');
            _valid = false;
        }
        if (_input.attr("name") === "EMAIL" && _input.val() === "") {
        } else if (_input.attr("name") === "EMAIL" && !isValidEmailAddress(_input.val())) {
            $(el).addClass('error');
            _valid = false;
        }
        if (_input.attr("name") === "PASSWORD") {
            var _has_password_error = false;
            if (_input.val() === "") {
            } else if (_input.val().length < 6) {
                _has_password_error = true;
            }
            if (_has_password_error) {
                $(el).addClass('error');
                _valid = false;
            }
        }
        if (_input.attr("name") === "CONFIRM_PASSWORD") {
            var _has_password_confirm_error = false;
            var _password = form.find(".req[name=PASSWORD]");
            if (_input.val() === "") {
            } else if (_input.val() !== _password.val()) {
                _has_password_confirm_error = true;
            }
            if (_has_password_confirm_error) {
                $(el).addClass('error');
                _valid = false;
            }
        }
    });
    return _valid;
}

function SendAjax(_action, _data, _callBack) {
    _callBack = _callBack || function () {
    };
    $.ajax({
        url: '/ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            'action': _action,
            'data': _data
        },
        error: function (data) {
            console.log(data);
        },
    }).done(function (data) {
        _callBack(data);
    });
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}


$.fn.serializeObject = function () {
    var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push": /^$/,
                "fixed": /^\d+$/,
                "named": /^[a-zA-Z0-9_]+$/
            };
    this.build = function (base, key, value) {
        base[key] = value;
        return base;
    };
    this.push_counter = function (key) {
        if (push_counters[key] === undefined) {
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };
    $.each($(this).serializeArray(), function () {
        // skip invalid keys
        if (!patterns.validate.test(this.name)) {
            return;
        }
        var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;
        while ((k = keys.pop()) !== undefined) {
            // adjust reverse_key
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
            // push
            if (k.match(patterns.push)) {
                merge = self.build([], self.push_counter(reverse_key), merge);
            }
            // fixed
            else if (k.match(patterns.fixed)) {
                merge = self.build([], k, merge);
            }
            // named
            else if (k.match(patterns.named)) {
                merge = self.build({}, k, merge);
            }
        }
        json = $.extend(true, json, merge);
    });
    return json;
};