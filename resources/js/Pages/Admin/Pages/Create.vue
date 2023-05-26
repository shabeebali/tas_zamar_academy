<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {onMounted, PropType, ref} from "vue";
onMounted(() => {
  console.log('Crate')
})

const props =defineProps({
  pageTitle: String,
  model: Object as PropType<{
    id?: number;
    title: string;
    content: string;
    url_key: string;
    meta_title: string;
    meta_keywords: string;
    meta_description: string;
  }>
})

const form = useForm({
  title: props.model.title,
  content: props.model.content,
  url_key: props.model.url_key,
  meta_title: props.model.meta_title,
  meta_keywords: props.model.meta_keywords,
  meta_description:props.model.meta_description
})

function save() {
  form.clearErrors()
  console.log(route().params)
  if(route().params.page) {
    form.put(route('admin.pages.update', { page: props.model.id}))
  } else {
    form.post(route('admin.pages.store'))
  }
}
</script>

<template>
  <Head :title="pageTitle"></Head>
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
            label="Page Title"
            :error="form.errors.title && form.errors.title.length > 0"
            :error-message="form.errors.title"
            v-model="form.title"></q-input>
        </div>
        <div class="col-12">
          <q-editor
            :toolbar="[
              ['left', 'center', 'right', 'justify','bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
              ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
              ['undo', 'redo'],
              ['viewsource']
            ]"
            v-model="form.content"></q-editor>
        </div>
        <div class="col-12">
          <q-input
            outlined
            dense
            :error="form.errors.url_key && form.errors.url_key.length > 0"
            :error-message="form.errors.url_key"
            label="URL Key"
            v-model="form.url_key"></q-input>
        </div>
      </div>
    </q-card-section>
    <q-separator/>
    <q-card-section>
      <div class="text-h6">SEO</div>
      <div class="row q-col-gutter-md">
        <div class="col-12">
          <q-input type="textarea" rows="3" label="Meta Title" outlined dense v-model="form.meta_title"></q-input>
        </div>
        <div class="col-12">
          <q-input type="textarea" rows="3" label="Meta Keywords" outlined dense v-model="form.meta_keywords"></q-input>
        </div>
        <div class="col-12">
          <q-input type="textarea" rows="3" label="Meta Description" outlined dense v-model="form.meta_description"></q-input>
        </div>
      </div>
    </q-card-section>
  </q-form>
</q-card>
</template>

<style scoped>

</style>
