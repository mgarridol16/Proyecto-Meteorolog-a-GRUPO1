document.addEventListener('DOMContentLoaded', () => {

    const filas = document.querySelectorAll('tbody tr[data-fecha]');

    const fechas = [];
    const humedades = [];

    filas.forEach(fila => {
        fechas.push(fila.dataset.fecha);
        humedades.push(Number(fila.dataset.humedad));
    });

    const ctx = document.getElementById('humedadChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Humedad (%)',
                data: humedades,
                borderColor: '#C1D96A',
                backgroundColor: 'rgba(13,110,253,0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#C1D96A'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value + '%'
                    }
                }
            }
        }
    });

});

