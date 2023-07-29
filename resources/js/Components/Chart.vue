<template>
    <div>
        <canvas :id="canvasId"></canvas>
    </div>
</template>

<script setup>
import {Chart} from 'Chart.js/auto';
import {onMounted} from "vue";

const props = defineProps({
    dataProps: {
        type: Array
    },
    labels: {
        type: Array
    },
    title: {
        type: String
    },
    canvasId: {
        type: String
    }
})


const renderChart = () => {
        new Chart(document.getElementById(props.canvasId).getContext('2d'), {
            type: 'bar',
            data: {
                labels: props.labels,
                datasets: [{
                    label: props.title,
                    data: props.dataProps,
                    backgroundColor: [
                        '#2ecc71',
                        '#e74c3c',
                        '#8e44ad',
                        '#d35400',
                        '#16a085'
                    ]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    fontSize: 22,
                    text: 'Example'
                }
            }
        });
}

onMounted(() => renderChart())
</script>
