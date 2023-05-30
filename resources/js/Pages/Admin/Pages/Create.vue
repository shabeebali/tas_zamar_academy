<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {onMounted, PropType, ref} from "vue";
import {CodeJar} from 'codejar';
import hljs from 'highlight.js'
import 'highlight.js/styles/github.css'
let jar = null;
onMounted(() => {
  //console.log('Crate')
  const editor = document.querySelector('.editor');
  const highlight = (editor) => {
    // highlight.js does not trims old tags,
    // let's do it by this hack.
    editor.textContent = editor.textContent;
    hljs.highlightElement(editor);
  };
  jar = CodeJar(editor as HTMLElement, highlight, {tab: '\t'});
  jar.updateCode(props.model.content)
})

function getCode() {
  if(jar) {
    console.log(jar.toString())
  }
}

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
  form.content = jar.toString()
  //console.log(route().params)
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
          <div class="text-subtitle2">Content</div>
          <div class="editor language-html hljs language-js"></div>
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
.editor {
  border-radius: 6px;
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
  font-family: 'Source Code Pro', monospace;
  font-size: 14px;
  font-weight: 400;
  height: 340px;
  letter-spacing: normal;
  line-height: 20px;
  padding: 10px;
  tab-size: 4;
}
</style>
