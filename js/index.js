let count = 0

document.getElementById("musique-btn").onclick = function () {
    if (count == 0) {
        count = 1;
        audio.play();
        let pause = document.getElementById("Off").textContent = "On";
    } else {
        count = 0
        audio.pause()
        let pause = document.getElementById("Off").textContent = "Off";
    }
}
