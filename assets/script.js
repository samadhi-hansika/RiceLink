function openLogin(){
    document.getElementById("loginModal").style.display = "flex";
}

function closeLogin(){
    document.getElementById("loginModal").style.display = "none";
}

function openRegister(){
    document.getElementById("registerModal").style.display = "flex";
}

function closeRegister(){
    document.getElementById("registerModal").style.display = "none";
}

function switchToRegister(){
    closeLogin();
    openRegister();
}