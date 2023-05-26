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
          :active="link.isActive"
          @click="router.visit(route(link.route))"
        >
          <q-item-section avatar>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6" :class="link.isActive ? 'text-lime-4':'text-zinc-400'" v-html="link.icon">
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
})
const sidebarLinks = [
  {
    label: 'Dashboard',
    icon: `
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
    `,
    route: 'admin.dashboard',
    isActive: route().current('admin.dashboard')
  },
  {
    label: 'Pages',
    icon: `
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
    `,
    route: 'admin.pages.index',
    isActive: route().current('admin.pages.*')
  }
]



function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

function logout() {
  router.post(route('admin.logout'))
}
</script>
