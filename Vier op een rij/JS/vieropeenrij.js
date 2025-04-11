var playerRed = "R";
var playerYellow = "Y";
var currPlayer = playerRed;
var gameOver = false;
var board;
var rows = 6;
var columns = 7;
var currColumns = [];
var tileBombActive = false;
var gravityInvertedActive = false;
var tileBreakerActive = false;

window.onload = function() {
    setGame();
    setupVolumeControl();
    setupSettings();
}

// Initialiseert het spelbord en maakt de tegels
function setGame() {
    // Maak een lege array voor het bord
    board = Array.from({ length: rows }, () => Array(columns).fill(' '));

    // Reset de huidige kolommen (laatste beschikbare rij in elke kolom)
    currColumns = Array(columns).fill(rows - 1);

    // Maak het visuele bord
    const boardElement = document.getElementById("board");
    boardElement.innerHTML = ""; // Leeg het bord-element

    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            let tile = document.createElement("div");
            tile.id = `${r}-${c}`;
            tile.classList.add("tile");
            tile.addEventListener("click", setPiece);
            boardElement.append(tile);
        }
    }
}

// Stelt volume-instellingen in voor geluiden
function setupVolumeControl() {
    const placePieceSound = document.getElementById('placePieceSound');
    const soundEffectsVolume = localStorage.getItem('soundEffectsVolume') || 100;
    placePieceSound.volume = soundEffectsVolume / 100;
}

// Configureert de spelinstellingen (bijv. volumeslider)
function setupSettings() {
    const volumeSlider = document.getElementById('volumeSlider');
    if (volumeSlider) {
        volumeSlider.addEventListener('input', function() {
            localStorage.setItem('soundEffectsVolume', this.value);
        });
        volumeSlider.value = localStorage.getItem('soundEffectsVolume') || 100;
    }
}

// Plaatst een steen of activeert een power-up bij klikken
function setPiece() {
    if (gameOver) return;
    if (tileBombActive) {
        activateTileBomb(this);
        tileBombActive = false;
        document.getElementById("board").classList.remove("tile-bomb-active");
        return;
    }
    if (tileBreakerActive) {
        activateTileBreaker(this);
        tileBreakerActive = false;
        document.getElementById("board").classList.remove("tile-breaker-active");
        return;
    }
    let coords = this.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);
    document.getElementById("placePieceSound").play();
    r = currColumns[c];
    if (r < 0) return;
    board[r][c] = currPlayer;
    let tile = document.getElementById(r + "-" + c);
    if (currPlayer === playerRed) {
        tile.classList.add("red-piece");
        currPlayer = playerYellow;
    } else {
        tile.classList.add("yellow-piece");
        currPlayer = playerRed;
    }
    currColumns[c] = r - 1;
    checkWinner();
    applyGravity();
    triggerRandomPower();
}

// Past zwaartekracht aan: standaard (naar beneden) of omgekeerd (omhoog)
function applyGravity() {
    if (gravityInvertedActive) {
        for (let c = 0; c < columns; c++) {
            let emptyRow = 0;
            for (let r = 0; r < rows; r++) {
                if (board[r][c] !== ' ') {
                    if (r !== emptyRow) {
                        board[emptyRow][c] = board[r][c];
                        board[r][c] = ' ';
                    }
                    emptyRow++;
                }
            }
        }
    } else {
        for (let c = 0; c < columns; c++) {
            let emptyRow = rows - 1;
            for (let r = rows - 1; r >= 0; r--) {
                if (board[r][c] !== ' ') {
                    if (r !== emptyRow) {
                        board[emptyRow][c] = board[r][c];
                        board[r][c] = ' ';
                    }
                    emptyRow--;
                }
            }
        }
    }
    updateVisualBoard();
}

// Controleert of er vier opeenvolgende stenen van dezelfde speler liggen
function checkWinner() {
    // Horizontaal
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] !== ' ' &&
                board[r][c] === board[r][c + 1] &&
                board[r][c + 1] === board[r][c + 2] &&
                board[r][c + 2] === board[r][c + 3]) {
                setWinner(r, c);
                return;
            }
        }
    }
    // Verticaal
    for (let c = 0; c < columns; c++) {
        for (let r = 0; r < rows - 3; r++) {
            if (board[r][c] !== ' ' &&
                board[r][c] === board[r + 1][c] &&
                board[r + 1][c] === board[r + 2][c] &&
                board[r + 2][c] === board[r + 3][c]) {
                setWinner(r, c);
                return;
            }
        }
    }
    // Diagonaal (\)
    for (let r = 0; r < rows - 3; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] !== ' ' &&
                board[r][c] === board[r + 1][c + 1] &&
                board[r + 1][c + 1] === board[r + 2][c + 2] &&
                board[r + 2][c + 2] === board[r + 3][c + 3]) {
                setWinner(r, c);
                return;
            }
        }
    }
    // Diagonaal (/)
    for (let r = 3; r < rows; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] !== ' ' &&
                board[r][c] === board[r - 1][c + 1] &&
                board[r - 1][c + 1] === board[r - 2][c + 2] &&
                board[r - 2][c + 2] === board[r - 3][c + 3]) {
                setWinner(r, c);
                return;
            }
        }
    }
}

// Bepaalt de winnaar en stopt het spel
function setWinner(r, c) {
    let winner = document.getElementById("winner");
    winner.innerText = (board[r][c] === playerRed) ? "Red Wins" : "Yellow Wins";
    gameOver = true;
}

// Update de visuele weergave van het bord
function updateVisualBoard() {
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            let tile = document.getElementById(r + "-" + c);
            tile.classList.remove("red-piece", "yellow-piece");
            if (board[r][c] === playerRed) {
                tile.classList.add("red-piece");
            } else if (board[r][c] === playerYellow) {
                tile.classList.add("yellow-piece");
            }
        }
    }
}

// Activeert de tile bomb power-up en wist de omliggende tegels
function activateTileBomb(tile) {
    let coords = tile.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);
    for (let i = r - 1; i <= r + 1; i++) {
        for (let j = c - 1; j <= c + 1; j++) {
            if (i >= 0 && i < rows && j >= 0 && j < columns && board[i][j] !== ' ') {
                board[i][j] = ' ';
                let affectedTile = document.getElementById(`${i}-${j}`);
                affectedTile.classList.remove("red-piece", "yellow-piece");

                // Update currColumns als de verwijderde steen de laagste steen in de kolom was
                if (currColumns[j] === i) {
                    currColumns[j]--;
                }
            }
        }
    }
    updateVisualBoard();
}

// Nieuwe functie: verwijdert enkel de aangeklikte tegel (tilebreaker) met animatie
function activateTileBreaker(tile) {
    tile.classList.add("tile-breaker-animation");

    setTimeout(() => {
        let coords = tile.id.split("-");
        let r = parseInt(coords[0]);
        let c = parseInt(coords[1]);
        if (board[r][c] !== ' ') {
            board[r][c] = ' ';
            tile.classList.remove("red-piece", "yellow-piece");

            // Update currColumns als de verwijderde steen de laagste steen in de kolom was
            if (currColumns[c] === r) {
                currColumns[c]--;
            }
        }
        applyGravity();
        tile.classList.remove("tile-breaker-animation");
    }, 500);
}

// Geeft de huidige speler een extra beurt
function activateExtraTurn() {
    displayMessage("Extra beurt! Jij mag nogmaals spelen.");
}

// Toggle voor zwaartekrachtomkering die actief blijft tot herhaaldelijk activeren
function activateGravityInverter() {
    gravityInvertedActive = !gravityInvertedActive;
    applyGravity();
    displayMessage(gravityInvertedActive ? "Gravity Inverted! De stenen bewegen nu omhoog." : "Standaard zwaartekracht hersteld!");
}

// Schudt de stukken in een kolom willekeurig
function shuffleColumn(colIndex) {
    let pieces = [];
    for (let r = 0; r < rows; r++) {
        if (board[r][colIndex] !== ' ') {
            pieces.push(board[r][colIndex]);
            board[r][colIndex] = ' ';
        }
    }
    for (let i = pieces.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [pieces[i], pieces[j]] = [pieces[j], pieces[i]];
    }
    let emptyRow = rows - 1;
    while (pieces.length) {
        board[emptyRow][colIndex] = pieces.pop();
        emptyRow--;
    }
    updateVisualBoard();
    displayMessage("Kolom " + (colIndex + 1) + " is gehusseld!");
}

// Verwijdert een willekeurige kolom
function activateColumnRemover() {
    let col = Math.floor(Math.random() * columns);
    for (let r = 0; r < rows; r++) {
        board[r][col] = ' ';
        let tile = document.getElementById(r + "-" + col);
        tile.classList.remove("red-piece", "yellow-piece");
    }
    currColumns[col] = rows - 1; // Reset de beschikbare rij in deze kolom
    updateVisualBoard();
    displayMessage("Kolom " + (col + 1) + " is verwijderd!");
}

// Verwijdert een willekeurige rij
function activateRowRemover() {
    let row = Math.floor(Math.random() * rows);
    for (let c = 0; c < columns; c++) {
        board[row][c] = ' ';
        let tile = document.getElementById(row + "-" + c);
        tile.classList.remove("red-piece", "yellow-piece");

        // Update currColumns als de verwijderde steen de laagste steen in de kolom was
        if (currColumns[c] === row) {
            currColumns[c]--;
        }
    }
    updateVisualBoard();
    displayMessage("Rij " + (row + 1) + " is verwijderd!");
}

// Wisselt alle stenen van de huidige speler naar de andere kleur
function activateColorSwitcher() {
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            if (board[r][c] === playerRed) {
                board[r][c] = playerYellow;
                let tile = document.getElementById(r + "-" + c);
                tile.classList.add("color-switch-animation");
                setTimeout(() => {
                    tile.classList.remove("red-piece", "color-switch-animation");
                    tile.classList.add("yellow-piece");
                }, 500);
            } else if (board[r][c] === playerYellow) {
                board[r][c] = playerRed;
                let tile = document.getElementById(r + "-" + c);
                tile.classList.add("color-switch-animation");
                setTimeout(() => {
                    tile.classList.remove("yellow-piece", "color-switch-animation");
                    tile.classList.add("red-piece");
                }, 500);
            }
        }
    }
    updateVisualBoard();
    displayMessage("Alle stenen zijn van kleur omgedraaid!");
}

// Dynamisch bericht weergeven in de indicator met een timeout waarin klikken niet mogelijk is
function displayMessage(msg) {
    let indicator = document.getElementById("power-up-indicator");
    if (indicator) {
        indicator.textContent = msg;
        indicator.classList.remove("d-none");
        indicator.style.pointerEvents = "none";  // blokkeren van klikken
        setTimeout(() => {
            indicator.classList.add("d-none");
            indicator.style.pointerEvents = "auto"; // klikfunctie weer toestaan
        }, 3000);
    }
}

// Activeert een willekeurige power-up of power-down
function triggerRandomPower() {
    let rand = Math.random();
    if (rand < 0.10) {
        displayMessage("Tile Bomb power-up! Klik op een tegel om de bom te activeren.");
        tileBombActive = true;
        document.getElementById("board").classList.add("tile-bomb-active");
    } else if (rand < 0.20) {
        displayMessage("Tile Breaker power-up! Klik op een tegel om deze te breken.");
        tileBreakerActive = true;
        document.getElementById("board").classList.add("tile-breaker-active");
    } else if (rand < 0.30) {
        activateExtraTurn();
    } else if (rand < 0.40) {
        activateGravityInverter();
    } else if (rand < 0.50) {
        let col = Math.floor(Math.random() * columns);
        shuffleColumn(col);
    } else if (rand < 0.60) {
        activateColumnRemover();
    } else if (rand < 0.70) {
        activateRowRemover();
    } else if (rand < 0.80) {
        activateColorSwitcher();
    }
}