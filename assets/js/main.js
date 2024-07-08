function loader() {
    document.querySelector('.preloader').classList.add("enter_pre");
}

function fadeout() {
    setInterval(loader, 4000);
}

window.onload = fadeout();