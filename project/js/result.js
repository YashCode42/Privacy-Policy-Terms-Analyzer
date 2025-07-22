document.addEventListener('DOMContentLoaded', function() {
    // Get analysis data from sessionStorage
    const data = JSON.parse(sessionStorage.getItem('analysisResult'));
    if (!data) {
        document.body.innerHTML = '<div style="color:red;text-align:center;margin-top:40px;">No analysis data found. Please analyze a document first.</div>';
        return;
    }

    document.getElementById('highlightedText').innerHTML = data.highlighted;
    document.getElementById('summary').innerHTML = data.summary;

    // Show score cards if available
    if (data.found && data.found.length > 0) {
        showScoreCards(data.found);
    }

    function showScoreCards(found) {
        const scoreSection = document.getElementById('scoreSection');
        const scoreCards = document.getElementById('scoreCards');

        // Count risk levels
        let high = 0, medium = 0, low = 0;
        found.forEach(item => {
            if(item.risk_level === 'high') high++;
            else if(item.risk_level === 'medium') medium++;
            else if(item.risk_level === 'low') low++;
        });

        // Only include cards with value > 0
        const cards = [
            { label: 'High', value: high, color: 'red' },
            { label: 'Medium', value: medium, color: 'yellow' },
            { label: 'Low', value: low, color: 'green' }
        ].filter(card => card.value > 0);

        const total = cards.reduce((sum, card) => sum + card.value, 0);

        // Calculate percentages so that their sum is exactly 100%
        let percentSum = 0;
        cards.forEach((card, idx) => {
            if (total > 0) {
                if (idx === cards.length - 1) {
                    card.percent = 100 - percentSum;
                } else {
                    card.percent = Math.round((card.value / total) * 100);
                    percentSum += card.percent;
                }
            } else {
                card.percent = 0;
            }
        });

        scoreCards.innerHTML = '';
        cards.forEach(card => {
            const div = document.createElement('div');
            div.className = `score-card ${card.color}`;
            div.innerHTML = `<div>${card.label}</div>
                             <div style="font-size:2rem;">${card.value}</div>
                             <div style="font-size:1.1rem; margin:2px 0;">${card.percent}%</div>
                             <div style="font-size:1rem; font-weight:normal; margin-top:4px;">Score</div>`;
            scoreCards.appendChild(div);
        });

        scoreSection.classList.remove('hidden');
    }
}); 