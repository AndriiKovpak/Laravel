/**
 * Created by bcooper on 3/2/2017.
 */

$(document).ready(function() {

    $('.DivisionDistrictsScroll ul').on('click', function(){
        $(this).toggleClass('toggleDistrict');
    });

    passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;

    $('.submit_user_form').on('click', function(){
        var password = $('#Password');
        var newPassword = $('#newPassword');
        var passwordV = password.val();
        var checkPasswordV = newPassword.val();
        if(passwordV || checkPasswordV){
            if(passwordRegex.test(passwordV)){
                password.parent().removeClass('has-danger');
                password.next().text('');
                if(passwordV == checkPasswordV){
                    newPassword.parent().removeClass('has-danger');
                    newPassword.next().text('');
                    $('#user_form').submit();
                }else{
                    newPassword.parent().addClass('has-danger');
                    newPassword.next().text('Passwords do not match!');
                }
            }else{
                newPassword.parent().removeClass('has-danger');
                newPassword.next().text('');
                password.parent().addClass('has-danger');
                password.next().text('Password must have at least eight characters, at least one capital letter, and at least one number.');
            }
        }else{
            password.parent().removeClass('has-danger');
            password.next().text('');
            $('#user_form').submit();
        }
    });

    $('#SecurityGroup').on('change', function(){
        if($(this).val() == '2'){
            $('#DivisionDistricts').show();
        }else{
            $('#DivisionDistricts').hide();
        }
    });

    $('#multiselect').multiselect();

});