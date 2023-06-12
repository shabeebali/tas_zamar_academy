<template>
  <q-layout view="lHh lpR fFf">

    <q-header class="bg-grey-2 text-grey-8">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer"/>

        <q-toolbar-title>
          {{page.props.title}}
        </q-toolbar-title>
        <q-space/>
        <q-btn flat round icon="account_circle">
          <q-menu style="min-width: 200px;">
            <q-item clickable v-close-popup class="text-red-8" @click="logout">
              <q-item-section avatar>
                <q-icon name="logout"></q-icon>
              </q-item-section>
              <q-item-section>
                <q-item-label>Logout</q-item-label>
              </q-item-section>
            </q-item>
          </q-menu>
        </q-btn>
      </q-toolbar>

    </q-header>

    <q-drawer
      class="bg-slate-900 text-white"
      show-if-above
      v-model="leftDrawerOpen"
      :width="240"
      side="left" bordered>
      <q-list>
        <q-item
          v-for="(link,i) in sidebarLinks"
          :key="i"
          clickable
          active-class="sidebar-item text-lime-6"
          :active="link.active"
          @click="router.visit(link.link)"
        >
          <q-item-section avatar>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6" :class="link.active ? 'text-lime-4':'text-zinc-400'" v-html="link.icon">
            </svg>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{link.label}}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>
    <q-page-container>
      <q-page class="q-pa-md">
        <slot/>
      </q-page>
    </q-page-container>

  </q-layout>
</template>
<style scoped>
.sidebar-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background-color: #cddc39 !important;
}
</style>
<script setup>
import {onMounted, ref} from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import {Notify} from "quasar";
const leftDrawerOpen = ref(false);
const page = usePage()
onMounted(() => {
  //console.log(page.props)
  if(page.props.flash.success) {
    Notify.create({
      type: 'positive',
      message: page.props.flash.success
    })
  }
  if(page.props.flash.info) {
    Notify.create({
      type: 'info',
      message: page.props.flash.info
    })
  }
  sidebarLinks.value = page.props.sidebar_links
})
const sidebarLinks = ref([])



function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

function logout() {
  router.post(route('admin.logout'))
}
</script>
