// Get elements
const chatIcon = document.getElementById('chatIcon');
const chatPopup = document.getElementById('chatPopup');
const closeBtn = document.getElementById('closeBtn');

window.onload = function () {
    chatPopup.style.display = 'none';
};

// Show the chat popup when clicking the chat icon
chatIcon.addEventListener('click', function () {
    // chatPopup.style.display = 'block';
    chatPopup.style.display = 'inline-block';
});

// Hide the chat popup when clicking the close button
closeBtn.addEventListener('click', function () {
    chatPopup.style.display = 'none';
});

const questionInput = document.getElementById('questionInput');
const questionList = document.getElementById('questionList');
const answerDisplay = document.getElementById('answerDisplay');
const submitButton = document.getElementById('submitButton');

questionInput.addEventListener('input', function () {
    const query = this.value.trim();

    if (query.length > 0) {
        // Fetch matching questions dynamically
        fetch('fetch_questions.php?q=' + encodeURIComponent(query))
            .then((response) => response.json())
            .then((data) => {
                questionList.innerHTML = '';
                if (data.length > 0) {
                    questionList.style.display = 'block';
                    answerDisplay.style.display = 'none';
                    data.forEach((item) => {
                        const li = document.createElement('li');
                        li.textContent = item.question;
                        li.className = 'list-group-item list-group-item-action';
                        li.addEventListener('click', () => {
                            questionInput.value = li.textContent;
                            questionList.style.display = 'none';
                            displayAnswer(item.answer);
                            submitButton.disabled = true; // Disable submit if a match is found
                        });
                        questionList.appendChild(li);
                    });
                } else {
                    questionList.style.display = 'none';
                    answerDisplay.style.display = 'none';
                    submitButton.disabled = false; // Enable submit if no matches are found
                }
            })
            .catch((error) => {
                console.error('Error fetching questions:', error);
                questionList.style.display = 'none';
            });
    } else {
        questionList.style.display = 'none';
        answerDisplay.style.display = 'none';
        submitButton.disabled = true; // Disable submit if input is empty
    }
});

// Function to display the answer below the input field
function displayAnswer(answer) {
    const answerAlert = answerDisplay.querySelector('.alert');
    answerAlert.textContent = answer || 'No answer available for this question.';
    answerDisplay.style.display = 'block';
}

// Hide the question list when clicking outside
document.addEventListener('click', function (e) {
    if (!questionList.contains(e.target) && !questionInput.contains(e.target)) {
        questionList.style.display = 'none';
    }
});