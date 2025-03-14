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
        for (let c = 0; c < columns - 3; c++) {
            if (board[r][c] != ' ') {
                if (board[r][c] == board[r][c + 1] && board[r][c + 1] == board[r][c + 2] && board[r][c + 2] == board[r][c + 3]) {
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
                if (board[r][c] == board[r + 1][c] && board[r + 1][c] == board[r + 2][c] && board[r + 2][c] == board[r + 3][c]) {
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
                if (board[r][c] == board[r + 1][c + 1] && board[r + 1][c + 1] == board[r + 2][c + 2] && board[r + 2][c + 2] == board[r + 3][c + 3]) {
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
                if (board[r][c] == board[r - 1][c + 1] && board[r - 1][c + 1] == board[r - 2][c + 2] && board[r - 2][c + 2] == board[r - 3][c + 3]) {
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

// Define the triggerPowerUp function
var rotationDegrees = 0;

function triggerPowerUp() {
    // Randomly activate a power-up
    let powerUpChance = Math.random();
    if (powerUpChance < 0.2) { // 20% chance to activate tile breaker
        tileBreakerActive = true;
        document.getElementById("board").classList.add("tile-breaker-active");
        alert("Tile Breaker Activated! Click on a tile to break it.");
    } else if (powerUpChance < 0.4) { // 20% chance to activate board rotation
        rotateBoard();
    }
}

function rotateBoard() {
    rotationDegrees = (rotationDegrees + 90) % 360;
    let boardElement = document.getElementById("board");

    boardElement.classList.remove("rotate-90", "rotate-180", "rotate-270");
    if (rotationDegrees === 90) {
        boardElement.classList.add("rotate-90");
    } else if (rotationDegrees === 180) {
        boardElement.classList.add("rotate-180");
    } else if (rotationDegrees === 270) {
        boardElement.classList.add("rotate-270");
    }

    setTimeout(() => {
        updateBoardAfterRotation();
        alert(`Board rotated ${rotationDegrees} degrees!`);
    }, 500); // Wait for the rotation animation to complete
}

function updateBoardAfterRotation() {
    let newBoard = Array.from({ length: rows }, () => Array(columns).fill(' '));
    let newCurrColumns = Array(columns).fill(rows - 1);

    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            if (board[r][c] !== ' ') {
                let newR, newC;
                if (rotationDegrees === 90) {
                    newR = c;
                    newC = rows - 1 - r;
                } else if (rotationDegrees === 180) {
                    newR = rows - 1 - r;
                    newC = columns - 1 - c;
                } else if (rotationDegrees === 270) {
                    newR = columns - 1 - c;
                    newC = r;
                }

                newBoard[newR][newC] = board[r][c];
            }
        }
    }

    // Make the tiles fall down to the new bottom
    for (let c = 0; c < columns; c++) {
        let emptyRow = rows - 1;
        for (let r = rows - 1; r >= 0; r--) {
            if (newBoard[r][c] !== ' ') {
                newBoard[emptyRow][c] = newBoard[r][c];
                if (emptyRow !== r) {
                    newBoard[r][c] = ' ';
                }
                emptyRow--;
            }
        }
        newCurrColumns[c] = emptyRow;
    }

    board = newBoard;
    currColumns = newCurrColumns;

    // Update the visual representation of the board
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            let tile = document.getElementById(r.toString() + "-" + c.toString());
            tile.classList.remove("red-piece", "yellow-piece");
            if (board[r][c] === playerRed) {
                tile.classList.add("red-piece");
            } else if (board[r][c] === playerYellow) {
                tile.classList.add("yellow-piece");
            }
        }
    }
}

function breakTile(tile) {
    let coords = tile.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);

    if (board[r][c] != ' ') {
        board[r][c] = ' '; // Clear the tile on the board
        tile.classList.remove("red-piece", "yellow-piece"); // Remove the piece visually

        // Make tiles above fall down
        for (let i = r; i > 0; i--) {
            board[i][c] = board[i - 1][c];
            let aboveTile = document.getElementById((i - 1).toString() + "-" + c.toString());
            let currentTile = document.getElementById(i.toString() + "-" + c.toString());
            if (board[i][c] == playerRed) {
                currentTile.classList.add("red-piece");
                currentTile.classList.remove("yellow-piece");
            } else if (board[i][c] == playerYellow) {
                currentTile.classList.add("yellow-piece");
                currentTile.classList.remove("red-piece");
            } else {
                currentTile.classList.remove("red-piece", "yellow-piece");
            }
        }
        board[0][c] = ' '; // Clear the topmost tile
        let topTile = document.getElementById("0-" + c.toString());
        topTile.classList.remove("red-piece", "yellow-piece");

        currColumns[c]++; // Update the column height
        tileBreakerActive = false; // Deactivate tile breaker
        document.getElementById("board").classList.remove("tile-breaker-active");
    }
}