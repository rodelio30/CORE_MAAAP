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

document.addEventListener('DOMContentLoaded', () => {
    const questionInput = document.getElementById('questionInput');
    const submitButton = document.getElementById('submitButton');
    const chatContainer = document.getElementById('chatContainer');
    const suggestionsContainer = document.getElementById('suggestions');

    // Enable submit button only when input is not empty
    questionInput.addEventListener('input', () => {
        submitButton.disabled = questionInput.value.trim() === '';
    });

    // Fetch suggestions and display them
    fetch('navita-data.php')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(item => {
                    const suggestionDiv = document.createElement('div');
                    suggestionDiv.className = 'chat-message suggestion';
                    suggestionDiv.textContent = item.question;

                    suggestionDiv.addEventListener('click', () => {
                        addChatBubble('user', item.question);
                        addTypingIndicator();
                        setTimeout(() => {
                            removeTypingIndicator();
                            addChatBubble('bot', item.answer);
                        }, 1000); // Simulate bot "typing"
                    });

                    suggestionsContainer.appendChild(suggestionDiv);
                });
            } else {
                suggestionsContainer.innerHTML = '<p>No suggestions available at the moment.</p>';
            }
        })
        .catch(error => console.error('Error fetching suggestions:', error));




    // Handle form submission
    document.getElementById('questionForm').addEventListener('submit', (e) => {
        e.preventDefault();

        // const question = questionInput.value.trim();
        const question = questionInput.value.trim().toLowerCase(); // Convert input to lowercase for comparison

        // Predefined responses for thank-you messages
        const thankYouResponses = [
            "You're welcome! 😊",
            "Happy to help! 🚀",
            "No problem at all!",
            "Glad I could assist! 😊",
            "Anytime! 👍",
            "You're very welcome! 🎉",
            "My pleasure! 😊",
            "No worries at all! 😃",
            "Always happy to help! 🤗",
            "Don't mention it! 😉",
            "I'm here to help! 💡",
            "Glad to be of service! ✅",
            "You're awesome! Keep going! 💪",
            "That's what I'm here for! 😃",
            "I'm happy to help anytime! 🎯",
            "Hope that helped! 😊",
            "You're most welcome! 🏆",
            "Helping is my thing! 😃",
            "It's my pleasure to assist! 🌟"
        ];


        if (["thanks", "thank you", "ty", "tnx", "salamat", "thx", "gracias", "merci", "danke", "arigato",
            "grazie", "kamsa", "kamsahamnida", "nice!", "nice", "xie xie", "spasibo", "dziękuję", "matur nuwun", "terima kasih", "dhanyavaad"].includes(question)) {
            addChatBubble('user', question);
            addTypingIndicator(); // Show bot is typing

            setTimeout(() => {
                removeTypingIndicator(); // Remove typing animation

                addChatBubble('bot', thankYouResponses[Math.floor(Math.random() * thankYouResponses.length)]);
            }, 1000);
            questionInput.value = ''; // Clear input
            submitButton.disabled = true;
            return;
        }

        // Send the question to the server to check if there's an existing answer
        fetch('fetch_answer.php?q=' + encodeURIComponent(question))
            .then(response => response.json())
            .then(data => {
                addChatBubble('user', question);
                addTypingIndicator();
                setTimeout(() => {
                    if (data.length > 0) {
                        removeTypingIndicator();
                        addChatBubble('bot', data[0].answer); // Show the found answer
                    } else {
                        // No answer found, inform the user and submit the question for review
                        removeTypingIndicator();
                        addChatBubble('bot', "Thanks for your question! It has been submitted. Please wait for an answer.");
                        submitQuestionToDatabase(question);
                    }
                }, 1000);
            })
            .catch(error => {
                addChatBubble('bot', "An error occurred. Please try again.");
                console.error('Error:', error);
            });

        questionInput.value = '';
        submitButton.disabled = true;
    });
    // Function to submit question to the database
    function submitQuestionToDatabase(question) {
        fetch('submit_question.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question: question })
        }).catch(error => console.error('Error submitting question:', error));
    }
    // Add chat bubble
    function addChatBubble(sender, message) {
        const chatContainer = document.getElementById('chatContainer');
        const bubble = document.createElement('div');
        bubble.className = `chat-message ${sender}-message`;
        bubble.innerHTML = `<p>${message}</p><span class="timestamp">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>`;
        chatContainer.appendChild(bubble);
        chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll to bottom
    }

    // Add typing indicator
    function addTypingIndicator() {
        const typingIndicator = document.createElement('div');
        typingIndicator.id = 'typingIndicator';
        typingIndicator.className = 'chat-message bot-message typing';
        typingIndicator.innerHTML = '<span>Bot is typing...</span>';
        chatContainer.appendChild(typingIndicator);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Remove typing indicator
    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
});