<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    total_donations: Number
})
</script>

<template>
    <Head title="Dashboard" />
    <AdminLayout>
        <div class="row">
            <div class="col-12 col-md-3">
                <q-card class="bg-grey-8 text-white">
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
        </div>
    </AdminLayout>
</template>
