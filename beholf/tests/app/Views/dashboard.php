<!-- Container for entire dashboard -->
<div class="container-fluid py-4">

  <!-- Dashboard header -->
  <header class="mb-4">
    <h2 class="fw-bold">Dashboard</h2>
  </header>

  <!-- Top statistics summary row -->
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm p-3 position-relative">
        <span class="h5 fw-semibold">Total User</span>
        <div class="display-6 mt-2"><?= $totalUser ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3 position-relative">
        <span class="h5 fw-semibold">Total Rapat</span>
        <div class="display-6 mt-2"><?= $totalRapat ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3 position-relative">
        <span class="h5 fw-semibold"><?= $levelUser?> on board </span>
        <div class="display-6 mt-2">Welcome, <?= $username ?></div>
      </div>
    </div>
  </div>


  <!-- Main dashboard content with sidebar -->
  <div class="row g-4">
    <!-- Left main content -->
    <main class="col-lg-12">
      <div class="card shadow-sm p-4 mb-4">
        <h4 class="mb-3">Statistik Transkrip</h4>
        <ul class="nav nav-tabs mb-3" id="statTabs">
          <li class="nav-item">
            <button class="nav-link active" data-range="weekly">Mingguan</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-range="monthly">Bulanan</button>
          </li>
        </ul>
        <canvas id="transkripChart" height="400"></canvas>
      </div>
    </main>
  </div>
</div>


<script>
  const weeklyStats = <?= json_encode($weeklyStats) ?>;
  const monthlyStats = <?= json_encode($monthlyStats) ?>;

  const ctx = document.getElementById("transkripChart").getContext("2d");

  function formatData(stats) {
    const labels = stats.map(d =>
      new Date(d.tanggal).toLocaleDateString("id-ID", { day: "numeric", month: "short" })
    );
    const values = stats.map(d => d.jumlah);
    return { labels, values };
  }

  let chartData = formatData(weeklyStats);

  let chart = new Chart(ctx, {
    type: "line",
    data: {
      labels: chartData.labels,
      datasets: [{
        label: "Jumlah Transkrip",
        data: chartData.values,
        backgroundColor: "rgba(235, 54, 54, 0.2)",
        borderColor: "rgba(211, 78, 95, 1)",
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, stepSize: 1 } }
    }
  });

  // Tab switching
  document.querySelectorAll("#statTabs button").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll("#statTabs button").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      const range = btn.dataset.range;
      const stats = range === "weekly" ? weeklyStats : monthlyStats;
      const { labels, values } = formatData(stats);

      chart.data.labels = labels;
      chart.data.datasets[0].data = values;
      chart.update();
    });
  });
</script>