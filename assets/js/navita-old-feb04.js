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
                        setTimeout(() => {
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

        const question = questionInput.value.trim();

        if (question) {
            // Add user's question as a chat bubble
            addChatBubble('user', question);

            // Simulate bot "typing" animation
            setTimeout(() => {

                // Send question to the backend via AJAX
                fetch('submit_question.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ question: question }),
                })
                    .then((response) => response.json())
                    .then((data) => {

                        if (data.success) {
                            addTypingIndicator();
                            // Add a bot response confirming the question was submitted
                            // addChatBubble('bot', 'Thanks for your question! It has been submitted. Please wait for an answer.');
                            // Simulate a delay of 1 second before displaying the bot's response
                            setTimeout(() => {
                                removeTypingIndicator();
                                addChatBubble('bot', 'Thanks for your question! It has been submitted. Please wait for an answer.');
                            }, 1000);
                        } else {
                            // Handle failure case
                            addChatBubble('bot', 'Sorry, there was an error submitting your question. Please try again.');
                        }

                        // Clear the input field and disable the submit button
                        questionInput.value = '';
                        submitButton.disabled = true;
                    })
                    .catch((error) => {
                        removeTypingIndicator();
                        addChatBubble('bot', 'An unexpected error occurred. Please try again.');
                        console.error('Error:', error);
                    });
            }, 500); // Simulate slight delay for bot typing
        }
    });

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