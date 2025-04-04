// Menu pagina functies
function startGame() {
    window.location.href = "pages/spel.html";
    // setGame(); // Removed as it is not defined in this file
}

function showInstructions() {
    var instructionsModal = new bootstrap.Modal(document.getElementById('instructionsModal'));
    instructionsModal.show();
}

function showSettings() {
    var settingsModal = new bootstrap.Modal(document.getElementById('settingsModal'));
    settingsModal.show();
}

document.getElementById("muziekSlider").addEventListener("input", function(e) {
    var audio = document.querySelector("audio");
    audio.volume = e.target.value / 100;
});