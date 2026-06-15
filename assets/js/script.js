// message in console
console.log("Student Management System Loaded");

// form validation
document.addEventListener("DOMContentLoaded", function(){

    let form = document.getElementById("studentForm");

    if(form){

        form.addEventListener("submit", function(e){

            let name = document.querySelector("input[name='name']").value;
            let phone = document.querySelector("input[name='phone']").value;

            if(name.length < 3){
                alert("Name must be at least 3 characters");
                e.preventDefault();
            }

            if(phone.length < 10){
                alert("Phone must be at least 10 digits");
                e.preventDefault();
            }

        });

    }

});