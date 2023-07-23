<template>
    <Head title="Order List"></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order List</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Table :createButton="false" :actionItems="menu" @onAccept="accept($event)" :records="orders" :cellFormatter="cellFormatter" :action="true" :conditionAction="conditionAction" :columnNames="columns"></Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
const props = defineProps({
    orders: {
        type: Array,
    }
});

const columns = [
    {
        name: "Nama Barang",
        real: "nama_barang"
    },
    {
        name: "Pembeli",
        real: "pembeli"
    },
    {
        name: "Qty",
        real: "qty"
    },
    {
        name: "Harga",
        real: "harga"
    },
    {
        name: "Total",
        real: "total"
    },
    {
        name: "Status",
        real: "status"
    }
];

const formatPrice = (value) => {
    let val = (value/1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

const menu = [
    {
        name: "Accept",
        emitName: "onAccept",
        classes: "inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150",
        icon: ""
    },
    {
        name: "Reject",
        emitName: "onReject",
        classes: "inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-1",
        icon: ""
    }
];
const conditionAction = {
    object: 'status',
    condition: 'pending'
}

const cellFormatter = (value, key) => {
    if('harga' === key)
        return 'Rp '+formatPrice(value);
    if('total' === key)
        return 'Rp '+formatPrice(value);
    return value;
}

const accept = (value) => {
    console.log(value)
}
</script>

<style scoped>

</style>
