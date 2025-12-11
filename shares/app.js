// Dummy data (replace with backend fetch later)
const investors = [
  { name: "Ego", capital: 5000 },
  { name: "Radley", capital: 3000 },
  { name: "Gilet", capital: 7000 }
];

// Populate ranking table
const rankingTable = document.querySelector("#rankingTable tbody");
investors
  .sort((a, b) => b.capital - a.capital)
  .forEach((inv, index) => {
    const row = `<tr>
      <td>${index + 1}</td>
      <td>${inv.name}</td>
      <td>${inv.capital}</td>
    </tr>`;
    rankingTable.innerHTML += row;
  });

// Dividend distribution chart
const dividendCtx = document.getElementById("dividendChart").getContext("2d");
new Chart(dividendCtx, {
  type: "pie",
  data: {
    labels: investors.map(i => i.name),
    datasets: [{
      data: investors.map(i => i.capital * 0.1), // Example: 10% dividend
      backgroundColor: ["#3498db", "#e74c3c", "#2ecc71"]
    }]
  }
});

// Yearly share value evolution chart
const shareValueCtx = document.getElementById("shareValueChart").getContext("2d");
new Chart(shareValueCtx, {
  type: "line",
  data: {
    labels: ["2021", "2022", "2023", "2024"],
    datasets: [{
      label: "Share Value",
      data: [100, 120, 150, 180],
      borderColor: "#9b59b6",
      fill: false
    }]
  }
});