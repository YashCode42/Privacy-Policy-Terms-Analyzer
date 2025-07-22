document.addEventListener('DOMContentLoaded', function() {
    const analyzeBtn = document.getElementById('analyzeBtn');
    const policyText = document.getElementById('policyText');
    const fileInput = document.getElementById('fileInput');
    const resultSection = document.getElementById('resultSection');
    const highlightedText = document.getElementById('highlightedText');
    const summary = document.getElementById('summary');

    // Read text from file
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(evt) {
            policyText.value = evt.target.result;
        };
        reader.readAsText(file);
    });

    // On Analyze button click
    analyzeBtn.addEventListener('click', function() {
        const text = policyText.value.trim();
        if (!text) {
            alert('Please paste text or upload a file!');
            return;
        }
        analyzeText(text);
    });

    // Call PHP API and display result
    function analyzeText(text) {
        analyzeBtn.disabled = true;
        analyzeBtn.textContent = 'Analyzing...';
        fetch('api/analyze.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ text })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                highlightedText.innerHTML = '';
                summary.innerHTML = '<span style="color:red">' + data.error + '</span>';
                document.getElementById('scoreSection').classList.add('hidden');
                resultSection.classList.remove('hidden');
            } else {
                // Save result to sessionStorage and redirect
                sessionStorage.setItem('analysisResult', JSON.stringify(data));
                window.location.href = 'result.html';
            }
        })
        .catch(err => {
            highlightedText.innerHTML = '';
            summary.innerHTML = '<span style="color:red">Could not connect to the server.</span>';
            document.getElementById('scoreSection').classList.add('hidden');
            resultSection.classList.remove('hidden');
        })
        .finally(() => {
            analyzeBtn.disabled = false;
            analyzeBtn.textContent = 'Analyze';
        });
    }
}); 