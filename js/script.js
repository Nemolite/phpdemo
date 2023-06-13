console.log(111)

const formElement = document.getElementById('registerform')?document.getElementById('registerform'):null;
const msg = document.getElementById('register-msg');
if((formElement)&&(msg)){
    formElement.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(formElement);

        const pass1 = formData.get('pass1');
        const pass2 = formData.get('pass2');
        console.log(pass2)
        if (pass1 !== pass2) {
            msg.innerText = 'Пароли не совпадают';
            return exit;
        } else {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    msg.innerHTML = this.responseText;
                }
                else {
                    msg.innerHTML = "Errors";
                }
            };
            xhr.open("POST", "/register.php");
            xhr.send(formData);
        }
    });
}

$(document).ready(function(){
    $('.slider').slick({
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    });
});
