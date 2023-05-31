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
  },
  {
    label: 'CMS Settings',
    icon: `
      <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
    `,
    route: 'admin.cms_settings',
    isActive: route().current('admin.cms_settings')
  }
]



function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

function logout() {
  router.post(route('admin.logout'))
}
</script>
