
<script setup lang="ts">

import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router } from '@inertiajs/vue3';
import { Head, Link } from "@inertiajs/vue3";
import {Dialog, Notify, QTable, QTableProps} from "quasar";
import {onMounted, PropType, ref} from "vue";

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
        field: 'phone',
        label: 'Phone',
        name: 'phone',
        align: 'left',
        sortable: true,
    },
    {
        field: 'comment',
        label: 'Comment',
        name: 'comment',
        align: 'left',
        format:(val:string) => val.length > 20 ? val.substring(0,20)+'...' : val,
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
        message: 'Do you want to delete this record',
        cancel: true
    }).onOk(() => {
        router.delete(route('admin.enquiries.destroy',id))
        Notify.create({
            type: 'info',
            message: 'Record deleted Successfully'
        })
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
    router.get(route('admin.enquiries.index',{
        page: reqProp.pagination.page,
        sort: reqProp.pagination.sortBy,
        descending: reqProp.pagination.descending,
        rpp: reqProp.pagination.rowsPerPage,
        search: reqProp.filter
    }))
}
const enquiry = ref(null)
const viewEnquiryDialog = ref(false)
function view(obj){
    enquiry.value = obj
    viewEnquiryDialog.value = true
}
</script>

<template>
    <Head title="Enquiries"></Head>
    <AdminLayout>
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Dashboard" class="cursor-pointer" @click="router.visit(route('admin.dashboard'))"></q-breadcrumbs-el>
            <q-breadcrumbs-el label="Enquiries"></q-breadcrumbs-el>
        </q-breadcrumbs>
        <q-table
            class="q-mt-md"
            :rows="items"
            :columns="columns"
            row-key="id"
            @request="onRequest"
            v-model:pagination="pagination"
        >
            <template v-slot:body-cell-actions="props">
                <q-td class="text-right">
                    <q-btn flat size="sm" round icon="visibility" color="primary" @click="view(props.row)"></q-btn>
                    <q-btn flat size="sm" round icon="delete" color="red" @click="deletePage(props.row.id)"></q-btn>
                </q-td>
            </template>
        </q-table>
        <q-dialog v-model="viewEnquiryDialog">
            <q-card class="" style="max-width: 500px; width: 90%;">
                <q-bar>
                    <q-space/>
                    <q-btn flat icon="close" v-close-popup size="sm"></q-btn>
                </q-bar>
                <q-card-section>
                    <q-markup-table separator="cell" wrap-cells class="full-width">
                        <tbody>
                            <q-tr>
                                <q-td>Name</q-td>
                                <q-td>{{enquiry.name}}</q-td>
                            </q-tr>
                            <q-tr>
                                <q-td>Email</q-td>
                                <q-td>{{enquiry.email}}</q-td>
                            </q-tr>
                            <q-tr>
                                <q-td>Phone</q-td>
                                <q-td>{{enquiry.phone}}</q-td>
                            </q-tr>
                            <q-tr>
                                <q-td>Comment</q-td>
                                <q-td>{{enquiry.comment}}</q-td>
                            </q-tr>
                        </tbody>
                    </q-markup-table>
                </q-card-section>
            </q-card>
        </q-dialog>
    </AdminLayout>
</template>

<style scoped>

</style>
