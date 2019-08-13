<script>
import { Line } from 'vue-chartjs'

export default {
  extends: Line,
  props: ['LastYearSales', 'ThisYearSales'],
  mounted() {
    this.renderChart(
      {
        labels: [
          '1月',
          '2月',
          '3月',
          '4月',
          '5月',
          '6月',
          '7月',
          '8月',
          '9月',
          '10月',
          '11月',
          '12月'
        ],
        datasets: [
          {
            label: '今年度',
            borderColor: '#d81b60',
            backgroundColor: '#d81b60',
            fill: false,
            data: this.ThisYearSales
          }, {
            label: '前年度',
            borderColor: '#3a87ad',
            backgroundColor: '#3a87ad',
            fill: false,
            data: this.LastYearSales
          }
        ]
      },
      {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          // xAxes: [
          //   {
          //     ticks: {}
          //   }
          // ],
          yAxes: [
            {
              ticks: {
                // min: 0,
                // max: 100000000,
                // stepSize: 5000000,
                callback: function(label, index, labels) {
                  return label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') +' 円';
                }
              }
            }
          ]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data){
              return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') +' 円';
            }
          }
        }
      }
    )
  }
}
</script>