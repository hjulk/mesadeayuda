$(document).ready(function (){

$('#btnLogin').click(function () {

        var diverrorLogin = $('#divErrorLogin');
        diverrorLogin.html('');

        var User = $("#user").val();
        var Pass = $("#password").val();

        if(User && Pass){
            diverrorLogin.html('');
            $.ajax({
                type: "post",
                url: "ingreso",
                data: {_method: 'post',
                    user: User,
                    password: Pass},
                success: function (data) {
                    var Valido = data['valido'];
                    var errors = data['errors'];
                    var rol = data['rol'];
                    if (Valido === 'true') {
                        if(rol === 1){
                            window.location.replace('home');
                        }else if(rol === 2){
                            window.location.replace('consulta');
                        }else if(rol === 3){
                            window.location.replace('registro');
                        }
                    }else{
                        $.each(errors,function(key, value){
                        if(value){
                            diverrorLogin.append(value+'<br>');
                            $('#panelError').show();
                        }
                    });
                    }
                },error: function () {
                    diverrorLogin.append('Hubo en error al ingresar al aplicativo.');
                }
            });
        }else{
            diverrorLogin.append('Debe digitar su identificación y clave.');
        }

  });

});
