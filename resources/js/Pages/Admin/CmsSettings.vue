<template>
    <Head title="CMS Settings"></Head>
<q-card>
    <q-toolbar>
        <q-space/>
        <q-btn
            label="Save"
            @click="save"
            color="primary"></q-btn>
    </q-toolbar>
    <q-card-section>
        <div class="row q-col-gutter-md">
            <div class="col-12">
                <div class="text-subtitle2">Header Content</div>
                <div class="header-editor editor language-html hljs language-js"></div>
            </div>
            <div class="col-12">
                <div class="text-subtitle2">Footer Content</div>
                <div class="footer-editor editor language-html hljs language-js"></div>
            </div>
        </div>
    </q-card-section>
</q-card>
</template>

<script setup lang="ts">

import {useForm, Head} from "@inertiajs/vue3";
import {Loading, Notify} from "quasar";
import {onMounted} from "vue";
import hljs from "highlight.js";
import {CodeJar} from "codejar";
let headerJar = null;
let footerJar = null
onMounted(() => {
    //console.log('Crate')
    const headerEditor = document.querySelector('.header-editor');
    const footerEditor = document.querySelector('.footer-editor');
    const headerHighlight = (headerEditor) => {
        // highlight.js does not trims old tags,
        // let's do it by this hack.
        headerEditor.textContent = headerEditor.textContent;
        hljs.highlightElement(headerEditor);
    };
    const footerHighlight = (footerEditor) => {
        // highlight.js does not trims old tags,
        // let's do it by this hack.
        footerEditor.textContent = footerEditor.textContent;
        hljs.highlightElement(footerEditor);
    };
    headerJar = CodeJar(headerEditor as HTMLElement, headerHighlight, {tab: '\t'});
    headerJar.updateCode(props.header_content)
    footerJar = CodeJar(footerEditor as HTMLElement, footerHighlight, {tab: '\t'});
    footerJar.updateCode(props.footer_content)
})
const props = defineProps({
    header_content: String,
    footer_content: String
})
const form = useForm({
    header_content: '',
    footer_content: ''
})

function save() {
    form.clearErrors();
    form.footer_content = footerJar.toString();
    form.header_content = headerJar.toString();
    Loading.show()
    form.post(route('admin.cms_settings'))
    Notify.create({
        type:'positive',
        message: 'Settings Saved'
    })
    Loading.hide()
}
</script>


<style scoped>
.editor {
    border-radius: 6px;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
    font-family: 'Source Code Pro', monospace;
    font-size: 14px;
    font-weight: 400;
    height: 200px;
    letter-spacing: normal;
    line-height: 20px;
    padding: 10px;
    tab-size: 4;
}
</style>
