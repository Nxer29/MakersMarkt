import Chart from 'chart.js/auto';

const data = window.__ADMIN_DASHBOARD__;
if (!data) console.warn('Admin dashboard data not found');

const isDark = document.documentElement.classList.contains('dark');
const textColor = isDark ? '#E5E7EB' : '#111827';
const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)';

// PIE: producten per type
const pieEl = document.getElementById('productsByTypeChart');
if (pieEl && data) {
    const labels = data.productsByType.map(x => x.type);
    const values = data.productsByType.map(x => x.total);

    new Chart(pieEl, {
        type: 'pie',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#4F46E5', '#06B6D4', '#22C55E', '#F59E0B', '#EF4444',
                    '#A855F7', '#64748B', '#14B8A6', '#F97316', '#3B82F6'
                ],
                borderColor: isDark ? '#111827' : '#FFFFFF',
                borderWidth: 2,
            }]
        },
        options: {
            plugins: { legend: { labels: { color: textColor } } }
        }
    });
}

// LINE: orders per dag
const lineEl = document.getElementById('ordersPerDayChart');
if (lineEl && data) {
    new Chart(lineEl, {
        type: 'line',
        data: {
            labels: data.orderDays,
            datasets: [{
                label: 'Orders',
                data: data.ordersPerDay,
                tension: 0.35,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,0.18)',
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        },
        options: {
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: { beginAtZero: true, ticks: { color: textColor }, grid: { color: gridColor } },
            },
            plugins: { legend: { labels: { color: textColor } } }
        }
    });
}
