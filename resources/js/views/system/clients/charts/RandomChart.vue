<template>
    <div class="small">
        <line-chart :chart-data="datacollection" :options="options"></line-chart>
        <button @click="fillData()">Randomize</button>
    </div>
</template>

<script>
    import LineChart from './LineChart.js'

    export default {
        components: {
            LineChart
        },
        data () {
            return {
                datacollection: null,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0
                        }
                    }
                }
            }
        },
        mounted () {
            this.fillData()
        },
        methods: {
            fillData () {
                this.datacollection = {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September' , 'October', 'November', 'December'],
                    datasets: [
                        {
                            label: 'Data One',
                            // backgroundColor: '#f87979',
                            data: [5, 10, 15, 25, 45, 70, 115, 185, 70, 75, 70, 60]
                        }
                    ]
                }
            },
            getRandomInt () {
                return Math.floor(Math.random() * (50 - 5 + 1)) + 5
            }
        }
    }
</script>

<style>
    .small {
        max-width: 600px;
        margin:  150px auto;
    }
</style>