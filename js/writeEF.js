// Text to type and loop
const text = "Grand Mutiara";
const typingTextElement = document.getElementById("typing-text");

let index = 0;
let isDeleting = false;

function type() {
    if (isDeleting) {
        typingTextElement.textContent = text.substring(0, index - 1);
        index--;
    } else {
        typingTextElement.textContent = text.substring(0, index + 1);
        index++;
    }

    // Determine typing speed
    let typingSpeed = isDeleting ? 150 : 200;

    // When text is fully typed, pause, then start deleting
    if (!isDeleting && index === text.length) {
        typingSpeed = 1000; // pause at end
        isDeleting = true;
    } else if (isDeleting && index === 0) {
        isDeleting = false;
    }

    // Repeat the function with the determined speed
    setTimeout(type, typingSpeed);
}

// Start typing effect
type();

//loading
