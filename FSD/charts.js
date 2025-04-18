drawChart();

function drawChart() {
    let subject_name = [];
    let subject_percentage = [];

    fetch('fetch_subjects.php')
    .then(response => response.json())
    .then(data => {
        data.forEach(element => {
            subject_name.push(element.sub_name);  
        });

        // Fetch percentages after subject names are fetched
        return fetch('api/report.php');
    })
    .then(response => response.json())
    .then(data => {
        subject_percentage = [...data]; // Store percentages

        // Now create the chart AFTER both fetches complete
        createChart(subject_name, subject_percentage);
    })
    .catch(error => console.error('Error fetching data:', error)); // Properly placed catch
}

function createChart(subject_name, subject_percentage) {
    const ctx = document.getElementById('chart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: subject_name, 
            datasets: [{
                label: 'Subject Percentage',
                data: subject_percentage,
                borderWidth: 0.5
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
}
