<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    total_donations: Number,
    total_enquiries: Number,
    data: Array
})
</script>

<template>
    <Head title="Dashboard" />
    <AdminLayout>
        <div class="row">
            <div class="col-12 justify-center flex">
                <q-img src="../../../assets/images/logo.png" width="100px"></q-img>
            </div>
        </div>
        <div class="text-subtitle1 text-center text-grey-7">
            Welcome to Zamar Music Academy Admin Dashboard
        </div>
        <q-separator/>
        <div class="row q-mt-md q-col-gutter-lg">
            <!--<div class="col-12 col-md-3">
                <q-card class="bg-blue-9 text-white">
                    <q-card-section horizontal class="items-center">
                        <q-card-section class="items-center">
                            Total Donations: {{Intl.NumberFormat("en-CA",{style:"currency",currency:"CAD"}).format(props.total_donations)}}
                        </q-card-section>
                        <q-card-section class="col-auto">
                            <q-btn color="white" @click="router.visit(route('admin.donations.index'))" icon="arrow_right" flat round></q-btn>
                        </q-card-section>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-12 col-md-3">
                <q-card class="bg-purple-9 text-white">
                    <q-card-section horizontal class="items-center">
                        <q-card-section class="items-center">
                            Total Enquiries: {{props.total_enquiries || '0'}}
                        </q-card-section>
                        <q-card-section class="col-auto">
                            <q-btn color="white" @click="router.visit(route('admin.enquiries.index'))" icon="arrow_right" flat round></q-btn>
                        </q-card-section>
                    </q-card-section>
                </q-card>
            </div>-->
            <template v-for="item in data" :key="item.label">
                <div class="col-12 col-md-3" v-if="item.label != 'Dashboard'">
                    <q-card class="bg-blue-10 text-white cursor-pointer" @click="router.visit(item.link)">
                        <q-card-section horizontal class="items-center justify-between">
                            <q-card-section class="items-center">
                                {{item.label}}
                            </q-card-section>
                                <q-btn color="white" :href="item.link" icon="arrow_right" flat round></q-btn>
                        </q-card-section>
                    </q-card>
                </div>
            </template>
        </div>
    </AdminLayout>
</template>
