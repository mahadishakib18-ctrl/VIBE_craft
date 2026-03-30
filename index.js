var nameError=document.getElementById('name-error'); 
var emailError = document.getElementById('email-error');
var phoneError = document.getElementById('phone-error');
var submitError = document.getElementById('submit-error');
var form = document.getElementById('labib');


function nameValidate(){
var name = document.getElementById('form-name').value;
if(name.length==0){
     nameError.innerHTML="Name is required";
     return false;

}
if(!name.match(/[a-zA-Z]+\s{1}[a-zA-Z]+/)){
    nameError.innerHTML="Name is invalid";
    return false;
}
nameError.innerHTML="success";
return true;
}



function emailValidate(){
    var email = document.getElementById('form-email').value;
    if(email.length==0){
         emailError.innerHTML="email is required";
         return false;
    
    }
    if(!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,4}$/)){
        emailError.innerHTML="Email is invalid";
        return false;
    }
    emailError.innerHTML="success";
    return true;


}


function phoneValidate(){
    var phone = document.getElementById('form-phone').value;
    if(phone.length==0){
         phoneError.innerHTML="phone is required";
         return false;
    
    }
    if(!phone.match(/^[0-9]{11}$/)){
        phoneError.innerHTML="phone is invalid";
        return false;
    }
    phoneError.innerHTML="success";
    return true;
}

form.addEventListener("submit",function(event){
event.preventDefault();
    const isNamevalid= nameValidate();
const isEmailvalid= emailValidate();
const isPhonevalid= phoneValidate();
if(isEmailvalid && isEmailvalid &&isPhonevalid){
submitError.innerHTML="Form is valid ready to submit";
submitErrorstyle.color='green';

}else{
    submitError.innerHTML="Please fix error ";
submitErrorstyle.color='red';
}
}
)