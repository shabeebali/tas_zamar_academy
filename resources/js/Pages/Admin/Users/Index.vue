
<script setup lang="ts">

import AdminLayout from "@/Layouts/AdminLayout.vue";
import {router, useForm, usePage} from '@inertiajs/vue3';
import { Head, Link } from "@inertiajs/vue3";
import {Dialog, Notify, QTable, QTableProps} from "quasar";
import {computed, onMounted, PropType, ref} from "vue";

const columns: QTableProps['columns'] = [
    {
        field: 'name',
        label: 'Name',
        name: 'name',
        align: 'left',
        sortable: true,
    },
    {
        field: 'email',
        label: 'Email',
        name: 'email',
        align: 'left',
        sortable: true,
    },
    {
        field: 'created_at',
        label: 'Created At',
        name: 'created_at',
        align: 'right',
        sortable: true,
    },
    {
        field: 'actions',
        label: '',
        name: 'actions',
        align: 'right',
        sortable: false,
    },
]

const props = defineProps({
    items: Array,
    pagination: Object as PropType<{
        page: number;
        sort: string;
        descending: boolean;
        total: number;
        rpp: number;
    }>
})

function deletePage(id: number) {
    Dialog.create({
        title: 'Confirm',
        message: 'Do you want to delete this user',
        cancel: true
    }).onOk(() => {
        router.delete(route('admin.users.destroy',id))
    })
}

const pagination = ref<QTableProps['pagination']> ({
    page: props.pagination.page,
    rowsPerPage: props.pagination.rpp,
    rowsNumber: props.pagination.total,
    descending: props.pagination.descending,
    sortBy: props.pagination.sort
})


function onRequest(reqProp: {pagination: QTableProps['pagination'], filter: string}) {
    router.get(route('admin.pages.index',{
        page: reqProp.pagination.page,
        sort: reqProp.pagination.sortBy,
        descending: reqProp.pagination.descending,
        rpp: reqProp.pagination.rowsPerPage,
        search: reqProp.filter
    }))
}
const page = usePage()
const showChangePassword = computed(() => {
    if(page.props.auth.roles) {
        const roles =  page.props.auth.roles as string[]
        return roles.find((val) => val === 'Admin' || val === 'super-admin')
    }
})

const changePasswordUserId = ref(0)
const changePasswordDialog = ref(false)
function openChangePasswordDialog(val: number)
{
    changePasswordUserId.value = val
    form.your_password = ''
    form.new_password = ''
    showPassword.value = false
    changePasswordDialog.value = true
}

const form = useForm({
    your_password: '',
    new_password: '',
})
const showPassword = ref(false)

function changePassword() {
    form.post(route('admin.users.change_password',{user: changePasswordUserId.value}),{
        onSuccess: () => {
            changePasswordDialog.value = false
        }
    })
}
</script>

<template>
    <Head title="Pages"></Head>
    <AdminLayout>
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Dashboard" class="cursor-pointer" @click="router.visit(route('admin.dashboard'))"></q-breadcrumbs-el>
            <q-breadcrumbs-el label="Users"></q-breadcrumbs-el>
        </q-breadcrumbs>
        <q-toolbar>
            <q-space/>
            <q-btn label="Create User" color="primary" @click="router.visit(route('admin.users.create'))"></q-btn>
        </q-toolbar>
        <q-table
            :rows="items"
            :columns="columns"
            row-key="id"
            @request="onRequest"
            v-model:pagination="pagination"
        >
            <template v-slot:body-cell-name="props">
                <q-td>
                    <Link class="text-primary" :href="route('admin.users.edit',props.row.id)">{{props.value}}</Link>
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td class="text-right">
                    <q-btn v-if="showChangePassword && props.row.id !== page.props.auth.user.id" flat size="sm" dense icon="edit" color="primary" label="Change Password" @click="openChangePasswordDialog(props.row.id)"></q-btn>
                    <q-btn flat size="sm" round icon="delete" color="red" @click="deletePage(props.row.id)"></q-btn>
                </q-td>
            </template>
        </q-table>
        <q-dialog v-model="changePasswordDialog">
            <q-card style="max-width: 500px; width: 90%">
                <q-card-section>
                    <div class="text-h6">Change Password</div>
                </q-card-section>
                <q-separator/>
                <q-card-section>
                    <q-form
                        autocorrect="off"
                        autocapitalize="off"
                        autocomplete="off"
                        spellcheck="false">
                        <div class="row q-col-gutter-md">
                            <div class="col-12">
                                <q-input
                                    outlined
                                    dense
                                    id="your_password"
                                    label="Your Password"
                                    :error="form.errors.your_password && form.errors.your_password.length > 0"
                                    :error-message="form.errors.your_password"
                                    v-model="form.your_password"></q-input>
                            </div>
                            <div class="col-12">
                                <q-input
                                    outlined
                                    dense
                                    label="New Password"
                                    :type="showPassword ? 'text':'password'"
                                    :error="form.errors.new_password && form.errors.new_password.length > 0"
                                    :error-message="form.errors.new_password"
                                    v-model="form.new_password">
                                    <template v-slot:append>
                                        <q-btn
                                            :icon="showPassword ? 'visibility_off' : 'visibility'"
                                            flat round
                                            size="sm"
                                            @click="showPassword = !showPassword"></q-btn>
                                    </template>
                                </q-input>
                            </div>
                        </div>
                    </q-form>
                </q-card-section>
                <q-separator/>
                <q-card-actions>
                    <q-btn color="green" label="Submit" @click="changePassword"></q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </AdminLayout>
</template>

<style scoped>

</style>
