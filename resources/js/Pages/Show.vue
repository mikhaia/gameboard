<script setup>
import Layout from './Layout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({ user: Object })

</script>

<template>
  <Layout>
    <Head title="Detail page" />
    <div class="grid justify-items-center h-screen content-center">
        <div>
          <h1 class="text-4xl">Hello {{ user.name }}!</h1>
          <div class="py-3">Here is your details:</div>
          <code class="block whitespace-pre border-dotted border-2 p-4" v-text="user"></code>
          {{ $filters.console(user) }}
        </div>
      </div>
  </Layout>
</template>