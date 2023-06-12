
<script setup lang="ts">

import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router } from '@inertiajs/vue3';
import { Head, Link } from "@inertiajs/vue3";
import {Dialog, Notify, QTable, QTableProps} from "quasar";
import {onMounted, PropType, ref} from "vue";

const columns: QTableProps['columns'] = [
  {
    field: 'title',
    label: 'Title',
    name: 'title',
    align: 'left',
    sortable: true,
  },
  {
    field: 'url_key',
    label: 'URL Key',
    name: 'url_key',
    align: 'left',
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
    message: 'Do you want to delete this page',
    cancel: true
  }).onOk(() => {
    router.delete(route('admin.pages.destroy',id))
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
</script>

<template>
  <Head title="Pages"></Head>
    <AdminLayout>
      <q-breadcrumbs>
        <q-breadcrumbs-el label="Dashboard" class="cursor-pointer" @click="router.visit(route('admin.dashboard'))"></q-breadcrumbs-el>
        <q-breadcrumbs-el label="Pages"></q-breadcrumbs-el>
      </q-breadcrumbs>
      <q-toolbar>
        <q-space/>
        <q-btn label="Create Page" color="primary" @click="router.visit(route('admin.pages.create'))"></q-btn>
      </q-toolbar>
      <q-table
        :rows="items"
        :columns="columns"
        row-key="id"
        @request="onRequest"
        v-model:pagination="pagination"
      >
        <template v-slot:body-cell-title="props">
          <q-td>
            <Link class="text-primary" :href="route('admin.pages.edit',props.row.id)">{{props.value}}</Link>
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td class="text-right">
              <q-btn flat size="sm" round icon="delete" color="red" @click="deletePage(props.row.id)"></q-btn>
          </q-td>
        </template>
      </q-table>
    </AdminLayout>
</template>

<style scoped>

</style>
