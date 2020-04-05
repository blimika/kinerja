<script>
    
!function ($) {
    "use strict";

    var SweetAlert = function () { };

    //examples 
    SweetAlert.prototype.init = function () {
        //Warning Message
        
        $("#sa-cek").click(function () {
            var peg_username = $('#SyncDataModal .modal-body #peg_username').val();
            var peg_password = $('#SyncDataModal .modal-body #peg_password').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            Swal.fire({
                title: 'Submit your Github username',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Look up',
                showLoaderOnConfirm: true,
                preConfirm: (peg_username) => {
                    return fetch(`//api.github.com/users/${peg_username}`)
                    .then(response => {
                        if (!response.ok) {
                        throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                        `Request failed: ${error}`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                if (result.value) {
                    Swal.fire({
                    title: `${result.value.peg_username}'s avatar`,
                    imageUrl: result.value.avatar_url
                    })
                }
                })

            
        });

    },
        //init
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

    //initializing 
    function ($) {
        "use strict";
        $.SweetAlert.init()
    }(window.jQuery);
</script>