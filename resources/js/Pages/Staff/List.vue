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
    nama: '',
    email: '',
    password: '',
    password_confirmation: '',
    jenis_kelamin: ''
});

const formEdit = useForm({
    id: '',
    nama: '',
    email: '',
    password: null,
    password_confirmation: null,
    jenis_kelamin: ''
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
        name: "Nama",
        real: "nama"
    },
    {
        name: "Email",
        real: "email"
    },
    {
        name: "Jenis Kelamin",
        real: "jenis_kelamin"
    },
];

const onNewRecord = () =>{
    triggerModal.value = true;
};

const onEditData = (event) =>{
    formEdit.id = event.id;
    formEdit.nama = event.name;
    formEdit.email = event.email;
    formEdit.jenis_kelamin = event.jenis_kelamin;

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

const createUser = () => {
    form.post(route('user.staff.create'), {
        preserveScroll: true,
        onSuccess: () =>{
            form.reset(),
                triggerModal.value = false;
        }
    });
};

const deleteData = () =>{
    formDelete.post(route('user.delete'), {
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

const editUser = () => {
    formEdit.post(route('user.staff.edit'), {
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
                Tambah Staff
            </h2>

            <form @submit.prevent="createUser" class="mt-6 space-y-6" enctype="multipart/form-data">
                <div>
                    <InputLabel for="nama" value="Nama" />

                    <TextInput
                        id="nama"
                        v-model="form.nama"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="nama"
                    />

                    <InputError :message="form.errors.nama" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        autocomplete="email"
                    />

                    <InputError :message="form.errors.email" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="password"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="password_confirmation" value="Password Confirmation" />

                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="password_confirmation"
                    />

                    <InputError :message="form.errors.password_confirmation" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="jenis_kelamin" value="Jenis Kelamin" />

                    <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="jenis_kelamin" name="jenis_kelamin" v-model="form.jenis_kelamin">
                        <option value="">-Pilih Jenis Kelamin--</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>

                    <InputError :message="form.errors.jenis_kelamin" class="mt-2" />
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
                Edit Staff
            </h2>

            <form @submit.prevent="editUser" class="mt-6 space-y-6" enctype="multipart/form-data">
                <div>
                    <InputLabel for="nama_edit" value="Nama" />

                    <TextInput
                        id="nama_edit"
                        v-model="formEdit.nama"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="nama"
                    />

                    <InputError :message="formEdit.errors.nama" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="email-edit" value="Email" />

                    <TextInput
                        id="email-edit"
                        v-model="formEdit.email"
                        type="email"
                        class="mt-1 block w-full"
                        autocomplete="email"
                        disabled="true"
                    />

                    <InputError :message="formEdit.errors.email" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="password-edit" value="Password" />

                    <TextInput
                        id="password-edit"
                        v-model="formEdit.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="password"
                    />

                    <InputError :message="formEdit.errors.password" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="password_confirmation-edit" value="Password Confirmation" />

                    <TextInput
                        id="password_confirmation-edit"
                        v-model="formEdit.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="password_confirmation"
                    />

                    <InputError :message="formEdit.errors.password_confirmation" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="jenis_kelamin-edit" value="Jenis Kelamin" />

                    <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="jenis_kelamin-edit" name="jenis_kelamin" v-model="formEdit.jenis_kelamin">
                        <option value="">-Pilih Jenis Kelamin--</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>

                    <InputError :message="formEdit.errors.jenis_kelamin" class="mt-2" />
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
