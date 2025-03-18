const reraDataPoints = [{ x: 0, y: 0 }, ...reraProgress.map(item => ({ x: item.timeline, y: item.work_completed }))];
const actualDataPoints = [{ x: 0, y: 0 }, ...actualProgress.map(item => ({ x: item.timeline, y: item.work_completed }))];

// Find the maximum timeline value in both datasets
const maxTimeline = Math.max(
  ...reraDataPoints.map(d => d.x),
  ...actualDataPoints.map(d => d.x),
  60
);

// Generate x-axis tick values (every 6 months)
const xTicks = [];
for (let i = 0; i <= maxTimeline; i += 6) {
  xTicks.push({ value: i });
}

function formatTimeline(months) {
  if (months < 12) {
    return `${months} Month`;
  }
  const years = months / 12;
  return Number.isInteger(years) ? `${years} Year` : `${years.toFixed(1)} Year`;
}

const ctx = document.getElementById('chart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    datasets: [
      {
        label: 'Progress as per RERA',
        data: reraDataPoints,
        borderColor: '#005395',
        borderWidth: 2,
        tension: 0,
        pointBackgroundColor: '#005395',
        pointRadius: reraDataPoints.map((_, i) => (i === 0 ? 0 : 0)),
      },
      {
        label: 'Actual Progress',
        data: actualDataPoints,
        borderColor: '#DE773E',
        borderWidth: 2,
        tension: 0,
        pointBackgroundColor: '#DE773E',
        pointRadius: actualDataPoints.map((_, i) => (i === 0 ? 0 : 0)),
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    layout: {
      padding: { top: 20 }
    },
    plugins: {
      tooltip: {
        callbacks: {
          title: (tooltipItems) => {
            const value = tooltipItems[0].raw.x;
            return formatTimeline(value);
          },
          label: (tooltipItem) => {
            return `Work Completed: ${tooltipItem.raw.y}%`;
          }
        }
      },
      legend: {
        display: true,
        position: 'bottom',
        labels: {
          usePointStyle: true,
          pointStyle: 'circle',
          font: { size: 12 },
          boxWidth: 8,
          boxHeight: 8
        }
      }
    },
    scales: {
      x: {
        type: 'linear',
        position: 'bottom',
        min: 0,
        max: maxTimeline,
        afterBuildTicks: (axis) => {
          axis.ticks = xTicks;
        },
        ticks: {
          callback: (value) => formatTimeline(value),
        },
        title: {
          display: true,
          text: 'Timeline',
          font: { size: 16 }
        },
        grid: {
          drawOnChartArea: false, // Hide grid lines
          drawTicks: false, // Hide small ticks
          color: 'transparent', // Ensure no grid lines
        },
        border: {
          display: true, // Show main axis line
          color: '#005395', // Make x-axis line blue
        }
      },
      y: {
        beginAtZero: true,
        max: 100,
        title: {
          display: true,
          text: 'Work Completed',
          font: { size: 16 }
        },
        ticks: {
          callback: (value) => value + '%',
        },
        grid: {
          drawOnChartArea: false, // Hide grid lines
          drawTicks: false, // Hide small ticks
          color: 'transparent', // Ensure no grid lines
        },
        border: {
          display: true, // Show main axis line
          color: '#005395', // Make x-axis line blue
        }
      }
    }
  }
});

document.addEventListener("DOMContentLoaded", function() {
    updateShareLinks();
});

document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.open-lightbox').forEach(link => {
      link.addEventListener('click', function() {
          let stepId = this.getAttribute('data-step-id');
          let firstImage = document.querySelector(`#lightbox-${stepId} a`);
          if (firstImage) {
              firstImage.click(); // Open the first image in the lightbox
          }
      });
  });
});

// Call the function to update share links when the page loads
// window.onload = updateShareLinks;