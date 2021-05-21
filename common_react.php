$(document).ready(function () {
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
});
window.ReactConsole = axios;
const rootElement = document.getElementById('infox');
$ = window.$;
axios.defaults.baseURL = '<?php echo (INFOX_CONSOLE_URL); ?>';
axios.defaults.headers.common['Authorization'] = '<?php echo (INFOX_TOKEN); ?>';
axios.interceptors.request.use(function (config) {
    Pace.start();
    return config;
}, function (error) {
    return Promise.reject(error);
});
axios.interceptors.response.use(function (response) {
    Pace.stop()
    return response;
}, function (error) {
    try {
        var err = error.response.data;
        if (error.response.status === 401) {
            window.location.href = '/?logout'
        }
    } catch (e) {
        var err = " - ";
    }
    alertify.alert(`${err} ${error}`);
    return Promise.reject(error);
});