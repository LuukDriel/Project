var playerRed = "R";
var playerYellow = "Y";
var currPlayer = playerRed;

var gameOver = false;
var board;

var rows = 6;
var columns = 7;
var currColumns = [];

var tileBreakerActive = false;

window.onload = function() {
    setGame();
}

function setGame() {
    board = [];
    currColumns = [5, 5, 5, 5, 5, 5, 5];

    for (let r = 0; r < rows; r++) {
        let row = [];
        for (let c = 0; c < columns; c++) {
            
            row.push(' ');
            
            let tile = document.createElement("div");
            tile.id = r.toString() + "-" + c.toString();
            tile.classList.add("tile");
            tile.addEventListener("click", setPiece);
            document.getElementById("board").append(tile);
        }
        board.push(row);
    }
}

function setPiece() {
    if (gameOver) {
        return;
    }

    if (tileBreakerActive) {
        // breek een blok
        breakTile(this);
        return;
    }

    //kijkt welk blok geklikt is
    let coords = this.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);

    // kijkt of het blok leeg is
    r = currColumns[c]; 

    if (r < 0) { //als het blok vol is
        return;
    }

    board[r][c] = currPlayer; //update het bord
    let tile = document.getElementById(r.toString() + "-" + c.toString());
    if (currPlayer == playerRed) {
        tile.classList.add("red-piece");
        currPlayer = playerYellow;
    }
    else {
        tile.classList.add("yellow-piece");
        currPlayer = playerRed;
    }

    r -= 1; //beweegt naar het volgende blok
    currColumns[c] = r; //update de array

    checkWinner();
    triggerPowerUp(); // checkt voor power-ups
}

function checkWinner() {
     // horizontaal
     for (let r = 0; r < rows; r++) {
         for (let c = 0; c < columns - 3; c++){
            if (board[r][c] != ' ') {
                if (board[r][c] == board[r][c+1] && board[r][c+1] == board[r][c+2] && board[r][c+2] == board[r][c+3]) {
                    setWinner(r, c);
                    return;
                }
            }
         }
    }

    // verticaal
    for (let c = 0; c < columns; c++) {
        for (let r = 0; r < rows - 3; r++) {
            if (board[r][c] != ' ') {
                if (board[r][c] == board[r+1][c] && board[r+1][c] == board[r+2][c] && board[r+2][c] == board[r+3][c]) {
                    setWinner(r, c);
                    return;
                }
            }
        }
    }

    // diagonaal
    for (let r = 0; r < rows - 3; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] != ' ') {
                if (board[r][c] == board[r+1][c+1] && board[r+1][c+1] == board[r+2][c+2] && board[r+2][c+2] == board[r+3][c+3]) {
                    setWinner(r, c);
                    return;
                }
            }
        }
    }

    // omgekeerd diagonaal
    for (let r = 3; r < rows; r++) {
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] != ' ') {
                if (board[r][c] == board[r-1][c+1] && board[r-1][c+1] == board[r-2][c+2] && board[r-2][c+2] == board[r-3][c+3]) {
                    setWinner(r, c);
                    return;
                }
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
    gameOver = true;
}

function triggerPowerUp() {
    // kans voor power-up is 30%
    let chance = Math.random();
    if (chance < 0.3) {
        let powerUpType = Math.floor(Math.random() * 3); // Kiest welke power-up

        switch (powerUpType) {
            case 0:
                activateTileBreaker();
                break;
            case 1:
                rotateBoard(90);
                showPowerUpIndicator("Board Rotated 90°!");
                break;
            case 2:
                rotateBoard(180);
                showPowerUpIndicator("Board Rotated 180°!");
                break;
            case 3:
                rotateBoard(-90);
                showPowerUpIndicator("Board Rotated -90°!");
                break;
        }
    }
}

function activateTileBreaker() {
    tileBreakerActive = true;
    showPowerUpIndicator("Tile Breaker Activated! Click a tile to break.");
    document.querySelectorAll(".tile").forEach(tile => {
        tile.classList.add("tile-breaker-active");
    });
}

function breakTile(tile) {
    // kijkt welke je klikt om te breken
    let coords = tile.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);

    if (board[r][c] != ' ') {
        board[r][c] = ' ';
        tile.classList.remove("red-piece");
        tile.classList.remove("yellow-piece");
        tile.classList.remove("tile-breaker-active");

        // Deactiveer tile breaker
        tileBreakerActive = false;
        document.querySelectorAll(".tile").forEach(tile => {
            tile.classList.remove("tile-breaker-active");
        });

        // Zorgt dat de blokken vallen
        applyGravity();
        updateBoard();
    }
}

function rotateBoard(degrees) {
    let newBoard = [];
    for (let r = 0; r < rows; r++) {
        let row = [];
        for (let c = 0; c < columns; c++) {
            row.push(' ');
        }
        newBoard.push(row);
    }

    if (degrees == 90) {
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < columns; c++) {
                newBoard[c][rows - 1 - r] = board[r][c];
            }
        }
    } else if (degrees == 180) {
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < columns; c++) {
                newBoard[rows - 1 - r][columns - 1 - c] = board[r][c];
            }
        }
    } else if (degrees == -90) {
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < columns; c++) {
                newBoard[columns - 1 - c][r] = board[r][c];
            }
        }
    }

    board = newBoard;
    applyGravity();
    updateBoard();
}

function applyGravity() {
    for (let c = 0; c < columns; c++) {
        let emptyRow = rows - 1;
        for (let r = rows - 1; r >= 0; r--) {
            if (board[r][c] != ' ') {
                board[emptyRow][c] = board[r][c];
                if (emptyRow != r) {
                    board[r][c] = ' ';
                }
                emptyRow--;
            }
        }
    }
}

function updateBoard() {
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            let tile = document.getElementById(r.toString() + "-" + c.toString());
            tile.classList.remove("red-piece");
            tile.classList.remove("yellow-piece");
            if (board[r][c] == playerRed) {
                tile.classList.add("red-piece");
            } else if (board[r][c] == playerYellow) {
                tile.classList.add("yellow-piece");
            }
        }
    }
}

function showPowerUpIndicator(message) {
    let indicator = document.getElementById("power-up-indicator");
    indicator.innerText = message;
    indicator.style.display = "block";

    setTimeout(() => {
        indicator.style.display = "none";
    }, 2000); // Laat melding 2 seconden zien
}

