<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Table from "@/Components/Table.vue";
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {ref} from "vue";
defineProps({
    records: {
        type: Array,
    },
});

const form = useForm({
    nama_barang: '',
    deskripsi: '',
    jenis_barang: '',
    stock_barang: '',
    harga_beli: '',
    harga_jual: '',
    gambar_barang: ''
});

const formEdit = useForm({
    id: '',
    nama_barang: '',
    deskripsi: '',
    jenis_barang: '',
    stock_barang: '',
    harga_beli: '',
    harga_jual: '',
    gambar_barang: ''
});

const formDelete = useForm({
    id: ''
});

const triggerModal = ref(false);
const confirmDelete = ref(false);
const modalEdit = ref(false);

const menu = [
    {
        name: "Edit",
        emitName: "onEdit",
        classes: "text-sky-500 hover:text-sky-600 inline-block p-2 w-full hover:bg-gray-100 rounded",
        icon: "fas fa-edit mr-2"
    },
    {
        name: "Delete",
        emitName: "onDelete",
        classes: "text-red-500 hover:text-red-600 inline-block p-2 w-full hover:bg-gray-100 rounded",
        icon: "fas fa-trash mr-2"
    }
];

const columns = [
    {
        name: "Nama Barang",
        real: "nama_barang"
    },
    {
        name: "Deskripsi",
        real: "deskripsi"
    },
    {
        name: "Jenis Barang",
        real: "jenis_barang"
    },
    {
        name: "Stock Barang",
        real: "stock_barang"
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
        name: "Gambar Barang",
        real: "gambar_barang"
    },
];

const onNewRecord = () =>{
    triggerModal.value = true;
};

const onEditData = (event) =>{
    formEdit.id = event.id;
    formEdit.nama_barang = event.nama_barang;
    formEdit.deskripsi = event.deskripsi;
    formEdit.jenis_barang = event.jenis_barang;
    formEdit.stock_barang = event.stock_barang;
    formEdit.harga_beli = event.harga_beli;
    formEdit.harga_jual = event.harga_jual;

    modalEdit.value = true;
}

const onDeleteData = (event) =>{
    formDelete.id = event.id;
    confirmDelete.value = true;
}

const closeModalDelete = () => {
    confirmDelete.value = false;
}

const closeModal = () => {
    triggerModal.value = false;
};

const closeModalEdit = () => {
    modalEdit.value = false;
};

const createBarang = () => {
    form.post(route('barang.create'), {
        preserveScroll: true,
        onSuccess: () =>{
            form.reset(),
            triggerModal.value = false;
        }
    });
};

const deleteData = () =>{
    formDelete.post(route('barang.delete'), {
        preserveScroll: true,
        onSuccess: () =>{
            formDelete.reset(),
            confirmDelete.value = false;
        }
    })
}

const cellFormatter = (value, key) => {
    if('gambar_barang' === key)
        return '<img src="..'+value+'"/>';
    return value;
}

const editBarang = () => {
    formEdit.post(route('barang.edit'), {
        preserveScroll: true,
        onSuccess: () =>{
            formEdit.reset(),
            modalEdit.value = false;
        }
    })
}
</script>

<template>
    <Head title="Barang"></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Barang</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Table :records="records" :processColumn="true" :cellFormatter="cellFormatter" :menuItems="menu" @onEdit="onEditData($event)" @onDelete="onDeleteData($event)"  @onCreate="onNewRecord" :columnNames="columns"></Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="triggerModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Tambah Barang
            </h2>

            <form @submit.prevent="createBarang" class="mt-6 space-y-6" enctype="multipart/form-data">
                <div>
                    <InputLabel for="nama_barang" value="Nama Barang" />

                    <TextInput
                        id="nama_barang"
                        ref="namaBarangInput"
                        v-model="form.nama_barang"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="nama-barang"
                    />

                    <InputError :message="form.errors.nama_barang" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="deskripsi" value="Deskripsi" />

                    <TextInput
                        id="deskripsi"
                        ref="deskripsiInput"
                        v-model="form.deskripsi"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="deskripsi"
                    />

                    <InputError :message="form.errors.deskripsi" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="jenis_barang" value="Jenis Barang" />

                    <TextInput
                        id="jenis_barang"
                        ref="jenisBarangiInput"
                        v-model="form.jenis_barang"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="jenis-barang"
                    />

                    <InputError :message="form.errors.jenis_barang" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="stock_barang" value="Stock Barang" />

                    <TextInput
                        id="stock_barang"
                        ref="stockBarangiInput"
                        v-model="form.stock_barang"
                        type="number"
                        class="mt-1 block w-full"
                        autocomplete="stock-barang"
                    />

                    <InputError :message="form.errors.stock_barang" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="harga_beli" value="Harga Beli" />

                    <TextInput
                        id="harga_beli"
                        ref="hargaBeliInput"
                        v-model="form.harga_beli"
                        type="number"
                        class="mt-1 block w-full"
                        autocomplete="harga-beli"
                    />

                    <InputError :message="form.errors.harga_beli" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="harga_jual" value="Harga Jual" />

                    <TextInput
                        id="harga_jual"
                        ref="jualBeliInput"
                        v-model="form.harga_jual"
                        type="number"
                        class="mt-1 block w-full"
                        autocomplete="harga-jual"
                    />

                    <InputError :message="form.errors.harga_jual" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="gambar_barang" value="Gambar Barang" />

                    <TextInput
                        id="gambar_barang"
                        ref="gambarBarangInput"
                        @input="form.gambar_barang = $event.target.files[0]"
                        type="file"
                        accept="image/png, image/jpeg, image/jpg"
                        class="mt-1 block w-full"
                        autocomplete="gambar-barang"
                    />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>

                    <InputError :message="form.errors.gambar_barang" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </Modal>

    <Modal :show="confirmDelete" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Anda yakin ingin menghapus?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Setelah data barang dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModalDelete"> Cancel </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteData"
                >
                    Delete
                </DangerButton>
            </div>
        </div>
    </Modal>

    <Modal :show="modalEdit" @close="closeModalEdit">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Edit Barang
            </h2>

            <form @submit.prevent="editBarang" class="mt-6 space-y-6" enctype="multipart/form-data">
                <div>
                    <InputLabel for="nama_barang-edit" value="Nama Barang" />

                    <TextInput
                        id="nama_barang-edit"
                        ref="namaBarangInput"
                        v-model="formEdit.nama_barang"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="nama-barang"
                    />

                    <InputError :message="formEdit.errors.nama_barang" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="deskripsi-edit" value="Deskripsi" />

                    <TextInput
                        id="deskripsi-edit"
                        ref="deskripsiInput"
                        v-model="formEdit.deskripsi"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="deskripsi"
                    />

                    <InputError :message="formEdit.errors.deskripsi" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="jenis_barang-edit" value="Jenis Barang" />

                    <TextInput
                        id="jenis_barang-edit"
                        ref="jenisBarangiInput"
                        v-model="formEdit.jenis_barang"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="jenis-barang"
                    />

                    <InputError :message="formEdit.errors.jenis_barang" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="stock_barang-edit" value="Stock Barang" />

                    <TextInput
                        id="stock_barang-edit"
                        ref="stockBarangiInput"
                        v-model="formEdit.stock_barang"
                        type="number"
                        class="mt-1 block w-full"
                        autocomplete="stock-barang"
                    />

                    <InputError :message="formEdit.errors.stock_barang" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="harga_beli-edit" value="Harga Beli" />

                    <TextInput
                        id="harga_beli-edit"
                        ref="hargaBeliInput"
                        v-model="formEdit.harga_beli"
                        type="number"
                        class="mt-1 block w-full"
                        autocomplete="harga-beli"
                    />

                    <InputError :message="formEdit.errors.harga_beli" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="harga_jual-edit" value="Harga Jual" />

                    <TextInput
                        id="harga_jual-edit"
                        ref="jualBeliInput"
                        v-model="formEdit.harga_jual"
                        type="number"
                        class="mt-1 block w-full"
                        autocomplete="harga-jual"
                    />

                    <InputError :message="formEdit.errors.harga_jual" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="gambar_barang-edit" value="Gambar Barang" />

                    <TextInput
                        id="gambar_barang-edit"
                        ref="gambarBarangInput"
                        @input="formEdit.gambar_barang = $event.target.files[0]"
                        type="file"
                        accept="image/png, image/jpeg, image/jpg"
                        class="mt-1 block w-full"
                        autocomplete="gambar-barang"
                    />
                    <progress v-if="formEdit.progress" :value="formEdit.progress.percentage" max="100">
                        {{ formEdit.progress.percentage }}%
                    </progress>

                    <InputError :message="formEdit.errors.gambar_barang" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="formEdit.processing">Save</PrimaryButton>
                    <SecondaryButton @click="closeModalEdit"> Cancel </SecondaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="formEdit.recentlySuccessful" class="text-sm text-gray-600">Edited.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </Modal>

</template>

<style scoped>

</style>
