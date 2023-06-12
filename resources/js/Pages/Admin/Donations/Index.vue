
<script setup lang="ts">

import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router } from '@inertiajs/vue3';
import { Head, Link } from "@inertiajs/vue3";
import {Dialog, Loading, Notify, QTable, QTableProps} from "quasar";
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
        field: 'payment_status',
        label: 'Status',
        name: 'payment_status',
        align: 'left',
        sortable: true,
    },
    {
        field: 'amount',
        label: 'Amount',
        name: 'amount',
        align: 'right',
        format: (val: number) => Intl.NumberFormat('en-CA',{currency:'CAD',style:"currency"}).format(val),
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
        router.delete(route('admin.donations.destroy',id))
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
    router.get(route('admin.donations.index',{
        page: reqProp.pagination.page,
        sort: reqProp.pagination.sortBy,
        descending: reqProp.pagination.descending,
        rpp: reqProp.pagination.rowsPerPage,
        search: reqProp.filter
    }))
}
const donation = ref(null)
const viewDonationDialog = ref(false)
async function view(obj){
    Loading.show()
    try {
        const res = await axios.get(route('admin.donations.show', {id: obj.id}))
        if(res.data) {
            donation.value = res.data
            viewDonationDialog.value = true
        }
    } catch (e) {
        console.log(e)
    }
    Loading.hide()
}

function getStatusColor(val:string) {
    if(val == 'pending') {
        return 'grey-8'
    }
    if(val == 'cancelled') {
        return 'orange-8'
    }
    if(val == 'failed') {
        return 'red-8'
    }
    if(val == 'paid') {
        return 'green-8'
    }
}
</script>

<template>
    <Head title="Donations"></Head>
    <AdminLayout>
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Dashboard" class="cursor-pointer" @click="router.visit(route('admin.dashboard'))"></q-breadcrumbs-el>
            <q-breadcrumbs-el label="Donations"></q-breadcrumbs-el>
        </q-breadcrumbs>
        <q-table
            class="q-mt-md"
            :rows="items"
            :columns="columns"
            row-key="id"
            @request="onRequest"
            v-model:pagination="pagination"
        >
            <template v-slot:body-cell-payment_status="props">
                <q-td>
                    <q-chip
                        text-color="white"
                        :label="props.value"
                        :color="getStatusColor(props.value)">
                    </q-chip>
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td class="text-right">
                    <q-btn flat size="sm" round icon="visibility" color="primary" @click="view(props.row)"></q-btn>
                    <q-btn flat size="sm" round icon="delete" color="red" @click="deletePage(props.row.id)"></q-btn>
                </q-td>
            </template>
        </q-table>
        <q-dialog v-model="viewDonationDialog">
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
                            <q-td>{{donation.name}}</q-td>
                        </q-tr>
                        <q-tr>
                            <q-td>Email</q-td>
                            <q-td>{{donation.email}}</q-td>
                        </q-tr>
                        <q-tr>
                            <q-td>Payment Status</q-td>
                            <q-td>
                                <q-chip
                                    text-color="white"
                                    :label="donation.payment_status"
                                    :color="getStatusColor(donation.payment_status)">
                                </q-chip>
                            </q-td>
                        </q-tr>
                        <q-tr>
                            <q-td>Amount</q-td>
                            <q-td>{{Intl.NumberFormat('en-CA',{currency:'CAD',style:"currency"}).format(donation.amount)}}</q-td>
                        </q-tr>
                        <q-tr>
                            <q-td>Location</q-td>
                            <q-td>{{donation.location}}</q-td>
                        </q-tr>
                        <q-tr>
                            <q-td>Address</q-td>
                            <q-td>{{donation.address}}</q-td>
                        </q-tr>

                        <q-tr>
                            <q-td>Comment</q-td>
                            <q-td>{{donation.comment}}</q-td>
                        </q-tr>
                        <q-tr>
                            <q-td>Transaction ID</q-td>
                            <q-td>{{donation.payment_transaction_id}}</q-td>
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
