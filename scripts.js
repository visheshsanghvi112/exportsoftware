//login page js 
// scripts.js

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('signupForm');

    form.addEventListener('submit', (event) => {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const email = document.getElementById('email').value.trim();

        if (username === '' || password === '' || email === '') {
            event.preventDefault();
            alert('Please fill out all required fields.');
        }
    });
});

//welcome page js 

// Example JavaScript code for interactions
console.log('Welcome page loaded');

// Time-based greeting
const greeting = () => {
    const hour = new Date().getHours();
    let message = 'Good day';
    if (hour < 12) {
        message = 'Good morning';
    } else if (hour < 18) {
        message = 'Good afternoon';
    } else {
        message = 'Good evening';
    }
    const header = document.querySelector('header h1');
    const currentTitle = header.textContent.split(',')[1].trim(); // Get the user's name
    header.textContent = `${message}, ${currentTitle}`;
};

greeting();
//end

// Example JavaScript code for interactions
console.log('Welcome page loaded');

function showLoading() {
    document.getElementById('loading').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('exportChart').getContext('2d');
    var exportChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Exports (in tons)',
                data: [120, 150, 180, 170, 210, 250, 300],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
