document.addEventListener("DOMContentLoaded", () => {
    const words = [
        { word: "APPLE", hint: "A popular red or green fruit" },
        { word: "ORANGE", hint: "A citrus fruit rich in vitamin C" },
        { word: "BANANA", hint: "A long yellow fruit" },
        { word: "GRAPE", hint: "Small round fruit used to make wine" },
        { word: "MANGO", hint: "Known as the king of fruits" },
        { word: "LEMON", hint: "A sour citrus fruit" },
        { word: "CHERRY", hint: "Small red fruit often used in desserts" },
        { word: "PINEAPPLE", hint: "Tropical fruit with a spiky exterior" },
        { word: "STRAWBERRY", hint: "A red fruit with tiny seeds on the outside" },
        { word: "WATERMELON", hint: "A large fruit with a green rind and red inside" },
        { word: "BLUEBERRY", hint: "A small blue fruit rich in antioxidants" },
        { word: "PEACH", hint: "A soft fruit with fuzzy skin" },
        { word: "COCONUT", hint: "A fruit with hard shell and white flesh" },
        { word: "KIWI", hint: "A small brown fruit with green flesh" }
    ];

    let selectedWordObj = {};
    let scrambledWord = "";
    let userInput = "";

    const wordContainer = document.getElementById("question");
    const inputField = document.getElementById("answer");
    const submitButton = document.getElementById("submit");
    const feedback = document.getElementById("feedback");
    const shuffleButton = document.getElementById("shuffle");
    const newWordButton = document.getElementById("new-word");
    const hintContainer = document.createElement("p");

    hintContainer.id = "hint";
    hintContainer.textContent = "";
    document.querySelector(".game-container").appendChild(hintContainer);

    function shuffleWord(word) {
        return word.split('').sort(() => Math.random() - 0.5).join('');
    }

    function newWord() {
        selectedWordObj = words[Math.floor(Math.random() * words.length)];
        scrambledWord = shuffleWord(selectedWordObj.word);
        wordContainer.textContent = scrambledWord;
        inputField.value = "";
        feedback.textContent = "";
        hintContainer.textContent = `Hint: ${selectedWordObj.hint}`;
    }

    function shuffleCurrentWord() {
        scrambledWord = shuffleWord(selectedWordObj.word);
        wordContainer.textContent = scrambledWord;
    }

    function checkAnswer() {
        userInput = inputField.value.toUpperCase();
        if (userInput === selectedWordObj.word) {
            feedback.textContent = "Correct!";
            feedback.classList.add("correct");
            feedback.classList.remove("wrong");
            setTimeout(newWord, 1500);
        } else {
            feedback.textContent = "Try Again!";
            feedback.classList.add("wrong");
            feedback.classList.remove("correct");
            inputField.classList.add("shake");
            setTimeout(() => inputField.classList.remove("shake"), 500);
        }
    }

    submitButton.addEventListener("click", checkAnswer);
    inputField.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            checkAnswer();
        }
    });

    shuffleButton.addEventListener("click", shuffleCurrentWord);
    newWordButton.addEventListener("click", newWord);

    newWord();
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
