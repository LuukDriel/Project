var playerRed = "R";
var playerYellow = "Y";
var currPlayer = playerRed;

var gameOver = false;
var board;

var rows = 6;
var columns = 7;
var currColumns = [5, 5, 5, 5, 5, 5, 5];  // Bijhouden van de rijen per kolom

function setGame() {
    board = [];
    currColumns = [5, 5, 5, 5, 5, 5, 5];  // Reset de kolommen

    // Maak het bord leeg en creëer de cells
    const boardElement = document.getElementById("board");
    boardElement.innerHTML = "";  // Zorg ervoor dat het bord wordt leeggehaald
    for (let r = 0; r < rows; r++) {
        let row = [];
        for (let c = 0; c < columns; c++) {
            row.push(' ');  // Vul de board-array met lege vakken
            let tile = document.createElement("div");
            tile.id = r.toString() + "-" + c.toString();
            tile.classList.add("tile");
            tile.addEventListener("click", setPiece);  // Voeg click event toe
            boardElement.appendChild(tile);  // Voeg tile toe aan het bord
        }
        board.push(row);
    }
}

function setPiece() {
    if (gameOver) {
        return;
    }

    let coords = this.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);

    // Vind de juiste lege rij in de geselecteerde kolom
    r = currColumns[c];

    if (r < 0) {
        return;  // Als de rij al vol is, doen we niks
    }

    board[r][c] = currPlayer;  // Update de board array met de huidige speler
    let tile = document.getElementById(r.toString() + "-" + c.toString());

    // Voeg de juiste kleur toe aan de tile
    if (currPlayer == playerRed) {
        tile.classList.add("red-piece");
        currPlayer = playerYellow;
    } else {
        tile.classList.add("yellow-piece");
        currPlayer = playerRed;
    }

    r -= 1; // Update de rij voor de volgende zet in de kolom
    currColumns[c] = r;  // Update de tracking array

    checkWinner();  // Controleer of er een winnaar is
}

function checkWinner() {
    // Horizontaal checken
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] != ' ' &&
                board[r][c] == board[r][c + 1] &&
                board[r][c + 1] == board[r][c + 2] &&
                board[r][c + 2] == board[r][c + 3]) {
                setWinner(r, c);
                return;
            }
        }
    }

    // Verticaal checken
    for (let c = 0; c < columns; c++) {
        for (let r = 0; r < rows - 3; r++) {
            if (board[r][c] != ' ' &&
                board[r][c] == board[r + 1][c] &&
                board[r + 1][c] == board[r + 2][c] &&
                board[r + 2][c] == board[r + 3][c]) {
                setWinner(r, c);
                return;
            }
        }
    }

    // Diagonaal checken (van linksboven naar rechtsonder)
    for (let r = 0; r < rows - 3; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] != ' ' &&
                board[r][c] == board[r + 1][c + 1] &&
                board[r + 1][c + 1] == board[r + 2][c + 2] &&
                board[r + 2][c + 2] == board[r + 3][c + 3]) {
                setWinner(r, c);
                return;
            }
        }
    }

    // Diagonaal checken (van rechtsboven naar linksonder)
    for (let r = 3; r < rows; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] != ' ' &&
                board[r][c] == board[r - 1][c + 1] &&
                board[r - 1][c + 1] == board[r - 2][c + 2] &&
                board[r - 2][c + 2] == board[r - 3][c + 3]) {
                setWinner(r, c);
                return;
            }
        }
    }
}

function setWinner(r, c) {
    let winner = document.getElementById("winner");
    if (board[r][c] == playerRed) {
        winner.innerText = "Red Wins";
    } else {
        winner.innerText = "Yellow Wins";
    }
    gameOver = true;  // Zet het spel als beëindigd
}

// Start het spel bij het laden van de pagina
window.onload = setGame;

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