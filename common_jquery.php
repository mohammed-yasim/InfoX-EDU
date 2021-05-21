<?php defined('INFOX') or die('No direct access allowed.');?>
<script>
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $(document).ajaxStart(function() { Pace.restart(); });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'Authorization': '<?php echo (INFOX_TOKEN); ?>'
            }
        });
        axios.defaults.baseURL = '<?php echo (INFOX_CONSOLE_URL); ?>';
        axios.defaults.headers.common['Authorization'] = '<?php echo (INFOX_TOKEN); ?>';
        axios.interceptors.request.use(function(config) {
            Pace.start();
            return config;
        }, function(error) {
            // Do something with request error
            return Promise.reject(error);
        });

        // Add a response interceptor
        axios.interceptors.response.use(function(response) {
            // Any status code that lie within the range of 2xx cause this function to trigger
            // Do something with response data
            Pace.stop()
            return response;
        }, function(error) {
            try {
                var err = error.response.data;
            } catch (e) {
                var err = e;
            }
            alertify.alert("Error", `${error}-${err}`);
            return Promise.reject(error);
        });

        $.fn.serializeObject = function() {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
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
</script>