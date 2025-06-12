const words = [
  { word: "elephant", hint: "A large gray animal with a trunk" },
  { word: "banana", hint: "A yellow fruit monkeys like" },
  { word: "umbrella", hint: "Used when it rains" },
  { word: "giraffe", hint: "Tall animal with a long neck" },
  { word: "computer", hint: "Used for typing, browsing, gaming" },
  { word: "chocolate", hint: "A sweet brown treat" },
  { word: "pyramid", hint: "Famous triangle-shaped building in Egypt" }
];

let currentIndex = 0;
let score = 0;

const answerInput = document.getElementById("answer");
const hintText = document.getElementById("hint");
const feedback = document.getElementById("feedback");

const scoreDisplay = document.createElement("p");
scoreDisplay.style.fontWeight = "bold";
scoreDisplay.style.marginTop = "20px";
document.querySelector(".bee-container").appendChild(scoreDisplay);

const progressDisplay = document.createElement("p");
progressDisplay.style.marginTop = "10px";
progressDisplay.style.fontStyle = "italic";
document.querySelector(".bee-container").appendChild(progressDisplay);

document.getElementById("play-word").addEventListener("click", () => {
  speakWord(words[currentIndex].word);
});

document.getElementById("hint-btn").addEventListener("click", () => {
  hintText.textContent = "Hint: " + words[currentIndex].hint;
});

document.getElementById("skip-btn").addEventListener("click", () => {
  moveToNextWord();
});

document.getElementById("submit-btn").addEventListener("click", checkAnswer);

answerInput.addEventListener("keypress", e => {
  if (e.key === "Enter") {
    checkAnswer();
  }
});

function speakWord(word) {
  const utterance = new SpeechSynthesisUtterance(word);
  utterance.rate = 0.8;
  speechSynthesis.speak(utterance);
}

function checkAnswer() {
  const userAnswer = answerInput.value.trim().toLowerCase();
  const correct = words[currentIndex].word.toLowerCase();

  if (userAnswer === correct) {
    answerInput.classList.add("correct");
    feedback.textContent = "✅ Correct!";
    playSound("correct");
    score++;
    updateScoreAndProgress();
    setTimeout(moveToNextWord, 1000);
  } else {
    answerInput.classList.add("incorrect");
    feedback.textContent = "❌ Try again!";
    playSound("wrong");
    setTimeout(() => {
      answerInput.classList.remove("incorrect");
    }, 500);
  }
}

function moveToNextWord() {
  if (currentIndex === words.length - 1) {
    saveScore(score);
    alert(`🎉 Game Over! Your final score is ${score}`);
    currentIndex = 0;
    score = 0;
  } else {
    currentIndex++;
  }

  answerInput.value = "";
  answerInput.classList.remove("correct", "incorrect");
  feedback.textContent = "";
  hintText.textContent = "";
  speakWord(words[currentIndex].word);
  updateScoreAndProgress();
}

function updateScoreAndProgress() {
  scoreDisplay.textContent = `Score: ${score}`;
  progressDisplay.textContent = `Word ${currentIndex + 1} of ${words.length}`;
}

function playSound(type) {
  const audio = new Audio();
  audio.src = type === "correct" ? "sounds/correct.mp3" : "sounds/wrong.mp3";
  audio.play();
}

function saveScore(score) {
  fetch('save_score.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `game_name=Spelling Bee&score=${score}`
  })
  .then(res => res.text())
  .then(data => console.log(data))
  .catch(err => console.error('Score saving failed', err));
}

// Initial display
updateScoreAndProgress();
