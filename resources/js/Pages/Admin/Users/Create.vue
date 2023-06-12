<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {onMounted, PropType, ref} from "vue";
const props =defineProps({
    pageTitle: String,
    model: Object as PropType<{
        id?: number;
        name: string;
        email: string;
        password: string;
        roles: string[]
    }>
})
const form = useForm({
    name: props.model.name,
    email: props.model.email,
    password: props.model.password,
    roles: props.model.roles,
})
function save() {
    form.clearErrors()
    if(route().params.user) {
        form.put(route('admin.users.update', { user: props.model.id}))
    } else {
        form.post(route('admin.users.store'))
    }
}
const showPassword = ref(false)
</script>

<template>
    <Head :title="pageTitle"></Head>
    <AdminLayout>
        <q-card>
            <q-toolbar>
                <q-space></q-space>
                <q-btn color="primary" label="Save" @click="save"></q-btn>
            </q-toolbar>
            <q-separator/>
            <q-form>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-12">
                            <q-input
                                outlined
                                dense
                                label="Name"
                                :error="form.errors.name && form.errors.name.length > 0"
                                :error-message="form.errors.name"
                                v-model="form.name"></q-input>
                        </div>
                        <div class="col-12">
                            <q-input
                                outlined
                                dense
                                label="Email"
                                :error="form.errors.email && form.errors.email.length > 0"
                                :error-message="form.errors.email"
                                v-model="form.email"></q-input>
                        </div>
                        <div class="col-12" v-if="!route().params.user">
                            <q-input
                                outlined
                                dense
                                label="Password"
                                :type="showPassword ? 'text':'password'"
                                :error="form.errors.password && form.errors.password.length > 0"
                                :error-message="form.errors.password"
                                v-model="form.password">
                                <template v-slot:append>
                                    <q-btn
                                        :icon="showPassword ? 'visibility_off' : 'visibility'"
                                        flat round
                                        size="sm"
                                        @click="showPassword = !showPassword"></q-btn>
                                </template>
                            </q-input>
                        </div>
                        <div class="col-12">
                            <q-select
                                multiple
                                :options="['Manager','Staff']"
                                outlined
                                dense
                                label="Roles"
                                :error="form.errors.roles && form.errors.roles.length > 0"
                                :error-message="form.errors.roles"
                                v-model="form.roles"></q-select>
                        </div>
                    </div>
                </q-card-section>
            </q-form>
        </q-card>
    </AdminLayout>
</template>

<style scoped>

</style>
