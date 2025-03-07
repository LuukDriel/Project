// Menu pagina functies
function startGame() {
    window.location.href = "pages/spel.html";
    setGame();
}

function showInstructions() {
    // Implement instructions modal if needed
}

function showSettings() {
    var settingsModal = new bootstrap.Modal(document.getElementById('settingsModal'));
    settingsModal.show();
}

document.getElementById("muziekSlider").addEventListener("input", function(e) {
    var audio = document.querySelector("audio");
    audio.volume = e.target.value / 100;
});