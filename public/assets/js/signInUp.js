$(document).ready(function() {

    /**
     * Custom validators for letters and numbers only.  Uppercase/lowercase letters and numbers (0-9).
     */

    $.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
    }, "Please use alphanumerical characters");


    $.validator.addMethod("atleast", function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).+$/.test(value);
    }, "Please use at least numbers, lower and upper case letters");


    $.validator.addMethod("imageSize", function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, "Image must not be largen than 500kb");

    $.validator.addMethod("nonUpperCaseEmail", function (value, element) {
        return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/.test(value);
    }, "Please introduce an email without upper case");


    $.validator.addMethod("isJpg", function (value, element) {
        return this.optional(element) || (element.files[0].name.substr(element.files[0].name.lastIndexOf('.') + 1) == 'jpg');
    }, "Please introduce a JPG image");

    $('#signin_form').validate({

        errorClass: "is-invalid",
        validClass: "is-valid",

        rules: {
            user_name: {
                required: true,
            },
            user_password:{
                required: true,
                minlength: 6,
                maxlength: 12,
                atleast: true
            },
        },

        messages: {
            user_name:{
                required: "Please enter your username or your email",
            },
            user_password:{
                required: "Please enter your password",
                minlength: "Your password must be at least 6 characters",
                maxlength: "Your password must be maximum 12 characters"
            },
        }
    });



    $('#register_form').validate({

        errorClass: "is-invalid",
        validClass: "is-valid",

        rules: {
            first_name: "required",
            username: {
                required: true,
                maxlength: 20,
                alphanumeric: true
            },
            password:{
                required: true,
                minlength: 6,
                maxlength: 12,
                atleast: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                nonUpperCaseEmail: true
            },
            birthdate: {
                required: true
            },
            customFile: {
                required: false,
                imageSize: 500000,
                isJpg: true
            }
        },

        messages: {
            first_name: "Please enter your name",
            username:{
                required: "Please enter your username",
                maxlength: "Your username must be maximum 20 characters",
                alphanumeric: "Please use alphanumerical characters"
            },
            password:{
                required: "Please enter your password",
                minlength: "Your password must be at least 6 characters",
                maxlength: "Your password must be maximum 12 characters"
            },
            confirm_password:{
                required: "Please repeat your password",
                equalTo: "Password doesn't match"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a correct email"
            },
            birthdate: {
                required: "Please enter your birthdate"
            }

        }
    });


    $('#formpassword').validate({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        /*feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },*/
        errorClass: "is-invalid",
        validClass: "is-valid",

        rules: {
            password:{
                required: true,
                minlength: 6,
                maxlength: 12,
                atleast: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        }
    });

    $('#formemail').validate({

        errorClass: "is-invalid",
        validClass: "is-valid",

        rules: {
            email: {
                required: true,
                email: true
            }
        }
    });

    $('#forpicture').validate({

        errorClass: "has-error",
        validClass: "has-success",

        rules: {
            customFile: {
                required: false,
                imageSize: 500000,
                isJpg: true
            }
        }
    });


    $('#register_form').submit(function () {
        /*element =  document.getElementById("customFile");
        if (element.files.length > 0 && element.files[0].size > 500000 ){
            return;
        }
        if (element.files.length > 0 && element.files[0].type != "image/jpeg" ){
            return;
        }
        first_name = document.getElementById("first_name")*/
        username = document.getElementById("username");
        password = document.getElementById("password");
        confirm_password = document.getElementById("confirm_password");
        email = document.getElementById("email")
        //birthdate = document.getElementById("birthdate");
        /*ajax('user', "first_name="+first_name.value+"&username="+username.value+"&password="+password.value
            +"&email="+email.value+"&birthdate="+birthdate.value+"&confirm_password="+confirm_password.value, function (http) {*/
        ajax('user', "username="+username.value+"&password="+password.value
            +"&email="+email.value+"&confirm_password="+confirm_password.value, function (http) {

            var obj = JSON.parse(http.responseText);

            console.log(obj);
            if(obj["response"]==="OK"){
                //uploadImageProfile();
                alert("Registered successfully!")
                window.location.replace("/");
            }else{
                document.getElementById("registre_incorrecte").classList.remove('d-none');
                document.getElementById("registre_incorrecte").innerHTML = obj["message"];
            }
        } );
        return false;
    });

    /*$('#customFile').change(function() {
        var i = $(this).next('label').clone();
        var file = $('#customFile')[0].files[0].name;
        $(this).next('label').text(file);
    });*/
});




function signIn(){
    user_name = document.getElementById("user_name");
    user_password = document.getElementById("user_password");
    ajax('signin', "user_name="+user_name.value+"&user_password="+user_password.value, function (http) {
        var obj = JSON.parse(http.responseText);
        if(obj["response"]==="OK"){
            window.location.replace("/");
        }else{
            document.getElementById("login_incorrecte").classList.remove('d-none');
        }
    } );
}



function ajax(url, params, method){
    var http = new XMLHttpRequest();
    http.open("POST", url, true);

//Send the proper header information along with the request
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            method(http);
        }
    }
    http.send(params);
}


function uploadImageProfile() {
    if(document.getElementById("customFile").files.length > 0){
        var file_data = $('#customFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('customFile', file_data, document.getElementById("username").value + ".jpg");
        $.ajax({
            url: '/profile/image',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function () {
                alert("Registered successfully!\nValidate your account in the email.")
                window.location.replace("/");
            }
        });
    }else{
        alert("Registered successfully!\nValidate your account in the email.")
        window.location.replace("/");
    }
}
