<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Table from "@/Components/Table.vue";
import Chart from "@/Components/Chart.vue"

const props = defineProps({
    data: {
        type: Array,
    },
});

const columns = [
    {
        name: "Nama Barang",
        real: "nama_barang"
    },
    {
        name: "Harga Beli",
        real: "harga_beli"
    },
    {
        name: "Harga Jual",
        real: "harga_jual"
    },
    {
        name: "Total Barang Terjual",
        real: "total_barang_terjual"
    },
    {
        name: "Total Penjualan",
        real: "total_penjualan"
    },
    {
        name: "Keuntungan",
        real: "profit"
    }
];

const barangName = [];
const qty = [];
const profit = [];
const total = [];

for(let i = 0; i < props.data.length; i++) {
    if(props.data[i].nama_barang) {
        barangName.push(props.data[i].nama_barang)
    }
    if(props.data[i].qty) {
        qty.push(props.data[i].qty)
    }
    if(props.data[i].profit) {
        profit.push(props.data[i].profit)
    }
    if(props.data[i].total) {
        total.push(props.data[i].total)
    }
}

const formatPrice = (value) => {
    let val = (value/1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

const cellFormatter = (value, key) => {
    if('nama_barang' !== key && 'qty' !== key)
        return formatPrice(value);
    return value;
}

const labels = ['barang 1', 'barang2'];

const exceptColumns = ['id','deleted_at','created_at','updated_at', 'barang_id', 'user_id', 'status'];
</script>

<template>
    <Head title="Reporting"></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporting</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Table :records="data" :processColumn="false" :columnNames="columns" :cellFormatter="cellFormatter" :createButton="false" :exceptColumns="exceptColumns"></Table>
                </div>
                <div class="bg-white mt-3 overflow-hidden shadow-sm sm:rounded-lg">
                    <h2 class="h2 text-center text-2xl">Grafik Penjualan</h2>
                    <div class="flex">
                        <div class="flex-auto w-64">
                            <Chart :dataProps="qty" :labels="barangName" :title='"Total Barang Terjual"' :canvasId="'totalPenjualan'"></Chart>
                        </div>
                        <div class="flex-auto w-64">
                            <Chart :dataProps="total" :labels="barangName" :title='"Total Penjualan"' :canvasId="'totalPenjualanBarang'"></Chart>
                        </div>
                        <div class="flex-auto w-64">
                            <Chart :dataProps="profit" :labels="barangName" :title='"Profit"' :canvasId="'profit'"></Chart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
