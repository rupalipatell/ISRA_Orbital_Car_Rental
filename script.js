console.log("Page loaded successfully!");
function toggleMenu() {
    document.querySelector('.nav-links').classList.toggle('active');
}

function moveCar() {
    const car = document.querySelector('.car');
    const body = document.body;
    
    // Move the car & page to the left
    car.style.transform = `translateX(-100vw)`;
    body.style.transform = `translateX(-100vw)`;

    // Load the login page after animation
    setTimeout(() => {
        window.location.href = "login.html";
    }, 2500); // Match transition time
}

// Ensure the new page slides in smoothly
window.addEventListener("load", () => {
    document.body.classList.add("loaded");
});
