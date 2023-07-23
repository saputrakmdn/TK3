<template>
    <Head title="Order"></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Products</h2>
                        <Transition enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0">
                            <p v-if="form.recentlySuccessful" class="mb-4 font-medium text-center text-lg text-green-600">Order berhasil diterima. Mohon tunggu staff kami untuk proses order.</p>
                        </Transition>
                        <Transition enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0">
                            <p v-if="form.hasErrors.valueOf('stock')" class="mb-4 font-medium text-center text-lg text-red-600">{{form.errors.stock}}</p>
                        </Transition>
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                            <div class="group relative" v-for="(value, index) in products">
                                <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                                    <img :src="value.gambar_barang" alt="Front of men&#039;s Basic Tee in black." class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                </div>
                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <h3 class="text-sm text-gray-700">
                                            <a href="#">
                                                {{value.nama_barang}}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">{{value.deskripsi}}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Rp {{formatPrice(value.harga_jual)}}</p>
                                </div>
                                <div class="flex justify-end mt-3">
                                    <PrimaryButton @click="onOrder(value)">Order</PrimaryButton>
                                </div>
                                <InputError :message="form.errors.formData" class="mt-2" />
                            </div>

                            <!-- More products... -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <Modal :show="modalQty" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Silakan masukann jumlah yang mau dibeli
            </h2>
            <div class="flex items-center mt-3">
                <h4 class="mr-4 font-heading font-medium">Qty:</h4>
                <TextInput v-model="form.qty" class="w-full px-3 py-2 text-green-700 border-2 focus:text-gray-700 border-gray-700 focus:border-gray-700  rounded-xl focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50" type="number"  />
            </div>
            <div class="flex items-center mt-3">
                <h4 class="mr-4 font-heading font-medium">Total:</h4>
                <h4 class="mr-4 font-heading font-medium">{{formatPrice(form.harga_jual * form.qty)}}</h4>
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submitOrder"
                >
                    Submit
                </PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {onBeforeMount, ref} from "vue";
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const form = useForm({
    id: '',
    qty: 1,
    harga_jual: 0
});

const props = defineProps({
    products: {
        type: Array,
    },
    status: {
        type: String
    },
});

const modalQty = ref(false);

const onOrder = (value) => {
    modalQty.value = true;
    form.id = value.id;
    form.harga_jual = value.harga_jual;
    console.log(form.harga_jual * form.qty);
}

const closeModal = () => {
    modalQty.value = false;
};

const formatPrice = (value) => {
    let val = (value/1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

const submitOrder = () => {
    form.post(route('order.submit'),{
        preserveScroll: true,
        onSuccess: () =>{
            form.reset(),
            modalQty.value = false;
        },
        onError: () =>{
            form.reset(),
            modalQty.value = false;
        }
    });
}
</script>

<style scoped>
input[type='number'] {
    -moz-appearance:textfield;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

</style>
