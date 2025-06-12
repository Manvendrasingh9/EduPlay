document.addEventListener("DOMContentLoaded", function () {
    const startButton = document.getElementById("start-game");
    const levelPopup = document.getElementById("level-popup");
    const startLevelButton = document.getElementById("start-level");
    const gameContainer = document.getElementById("game-container");
    const changeLevelButton = document.getElementById("change-level");
    const answerInput = document.getElementById("answer");
    const submitButton = document.getElementById("submit-answer");
    const feedback = document.getElementById("feedback");
    const puzzleTypeSelect = document.getElementById("puzzle-type");
    
    let correctAnswer;
    let selectedLevel = 1;
    let selectedPuzzle = "random";

    startButton.addEventListener("click", function () {
        levelPopup.style.display = "flex";
    });

    startLevelButton.addEventListener("click", function () {
        selectedLevel = parseInt(document.getElementById("level-select").value);
        selectedPuzzle = puzzleTypeSelect.value;
        levelPopup.style.display = "none";
        startButton.style.display = "none";
        gameContainer.style.display = "block";
        generateQuestion();
    });

    changeLevelButton.addEventListener("click", function () {
        gameContainer.style.display = "none";
        startButton.style.display = "block";
        startButton.style.margin = "auto";
    });

    function generateQuestion() {
        let num1, num2;
        if (selectedPuzzle === "random") {
            const types = ["addition", "subtraction", "multiplication", "division"];
            selectedPuzzle = types[Math.floor(Math.random() * types.length)];
        }

        switch (selectedLevel) {
            case 1:
                num1 = Math.floor(Math.random() * 10) + 1;
                num2 = Math.floor(Math.random() * 10) + 1;
                break;
            case 2:
                num1 = Math.floor(Math.random() * 50) + 10;
                num2 = Math.floor(Math.random() * 50) + 10;
                break;
            case 3:
                num1 = Math.floor(Math.random() * 100) + 50;
                num2 = Math.floor(Math.random() * 100) + 50;
                break;
        }
        
        switch (selectedPuzzle) {
            case "addition":
                correctAnswer = num1 + num2;
                document.getElementById("question").innerText = `What is ${num1} + ${num2}?`;
                break;
            case "subtraction":
                correctAnswer = num1 - num2;
                document.getElementById("question").innerText = `What is ${num1} - ${num2}?`;
                break;
            case "multiplication":
                correctAnswer = num1 * num2;
                document.getElementById("question").innerText = `What is ${num1} × ${num2}?`;
                break;
            case "division":
                num2 = Math.floor(Math.random() * 9) + 1; // Ensure num2 is between 1 and 9
                num1 = num2 * (Math.floor(Math.random() * 10) + 1); // Make num1 a multiple of num2
                correctAnswer = num1 / num2;
                document.getElementById("question").innerText = `What is ${num1} ÷ ${num2}?`;
                break;
            case "mixed":
                let operations = ["addition", "subtraction", "multiplication", "division"];
                selectedPuzzle = operations[Math.floor(Math.random() * operations.length)];
                generateQuestion();
                return;
        }
    }

    function checkAnswer() {
        let userAnswer = parseFloat(answerInput.value);
        
        if (!isNaN(userAnswer)) {
            if (userAnswer === correctAnswer) {
                feedback.innerText = "Correct! 🎉";
                feedback.style.color = "green";
                setTimeout(() => {
                    feedback.innerText = "";
                    answerInput.value = "";
                    generateQuestion();
                }, 1000);
            } else {
                feedback.innerText = "Try again! ❌";
                feedback.style.color = "red";
                answerInput.value = "";
            }
        }
    }

    submitButton.addEventListener("click", checkAnswer);
    answerInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            checkAnswer();
        }
    });
});
function saveScore(score) {
    fetch('save_score.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `game_name=Word Builder&score=${score}`
    })
    .then(res => res.text())
    .then(data => console.log(data))
    .catch(err => console.error('Score saving failed', err));
}