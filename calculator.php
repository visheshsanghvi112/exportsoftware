<!-- calculator.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Floating Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #calculator {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 550px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            padding: 20px;
        }

        #calculator #close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            text-align: center;
            line-height: 30px;
            cursor: pointer;
        }

        #calculator #display {
            width: 100%;
            height: 50px;
            font-size: 24px;
            text-align: right;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
        }

        #calculator #buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
        }

        #calculator button {
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #f0f0f0;
        }

        #calculator button.operator {
            background-color: #ff6f61;
            color: #fff;
        }

        #calculator button.equals {
            background-color: #007bff;
            color: #fff;
            grid-column: span 2;
        }

        #calculator button.clear {
            background-color: #ff6f61;
            color: #fff;
            grid-column: span 2;
        }

        #calculator button.memory {
            background-color: #f0ad4e;
            color: #fff;
        }

        #history {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            margin-top: 10px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div id="calculator">
        <button id="close-btn">X</button>
        <input type="text" id="display" disabled>
        <div id="buttons">
            <button onclick="appendNumber(7)">7</button>
            <button onclick="appendNumber(8)">8</button>
            <button onclick="appendNumber(9)">9</button>
            <button onclick="appendOperation('/')">/</button>
            <button onclick="appendNumber(4)">4</button>
            <button onclick="appendNumber(5)">5</button>
            <button onclick="appendNumber(6)">6</button>
            <button onclick="appendOperation('*')">*</button>
            <button onclick="appendNumber(1)">1</button>
            <button onclick="appendNumber(2)">2</button>
            <button onclick="appendNumber(3)">3</button>
            <button onclick="appendOperation('-')">-</button>
            <button onclick="appendNumber(0)">0</button>
            <button onclick="appendOperation('+')">+</button>
            <button onclick="calculateResult()" class="equals">=</button>
            <button onclick="clearDisplay()" class="clear">C</button>
            <button onclick="appendOperation('Math.sqrt(')">√</button>
            <button onclick="appendOperation('Math.sin(')">sin</button>
            <button onclick="appendOperation('Math.cos(')">cos</button>
            <button onclick="appendOperation('Math.tan(')">tan</button>
            <button onclick="appendOperation('Math.log(')">log</button>
            <button onclick="memoryStore()" class="memory">M+</button>
            <button onclick="memoryRecall()" class="memory">MR</button>
            <button onclick="memoryClear()" class="memory">MC</button>
            <button onclick="appendOperation('**')">^</button>
            <button onclick="appendOperation('%')">%</button>
        </div>
        <h4>History</h4>
        <div id="history"></div>
    </div>
    <script>
        let memoryValue = 0; // Variable to store memory value
        const historyLog = []; // Array to keep track of history

        document.addEventListener('DOMContentLoaded', () => {
            const calculator = document.getElementById('calculator');
            const closeBtn = document.getElementById('close-btn');
            const display = document.getElementById('display');
            const historyDiv = document.getElementById('history');

            function toggleCalculator() {
                calculator.style.display = calculator.style.display === 'none' ? 'block' : 'none';
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'F12') {
                    event.preventDefault();
                    toggleCalculator();
                }
            });

            closeBtn.addEventListener('click', () => {
                calculator.style.display = 'none';
            });

            window.appendNumber = function(number) {
                display.value += number;
            };

            window.appendOperation = function(operation) {
                display.value += ' ' + operation + ' ';
            };

            window.calculateResult = function() {
                try {
                    const result = eval(display.value.replace(/Math.sqrt/g, 'Math.sqrt').replace(/\^/g, '**'));
                    display.value = result;
                    historyLog.push(`${display.value} = ${result}`); // Log the calculation
                    updateHistory();
                } catch (e) {
                    display.value = 'Error';
                }
            };

            window.clearDisplay = function() {
                display.value = '';
            };

            window.memoryStore = function() {
                memoryValue = eval(display.value);
                alert('Stored in memory: ' + memoryValue);
            };

            window.memoryRecall = function() {
                display.value += memoryValue;
            };

            window.memoryClear = function() {
                memoryValue = 0;
                alert('Memory cleared');
            };

            function updateHistory() {
                historyDiv.innerHTML = ''; // Clear the current history display
                historyLog.forEach(entry => {
                    const div = document.createElement('div');
                    div.textContent = entry;
                    historyDiv.appendChild(div);
                });
            }
        });
    </script>
</body>
</html>
