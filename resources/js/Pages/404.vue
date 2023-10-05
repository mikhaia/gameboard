<script setup>
import Layout from './Layout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Layout>
    <Head title="404 / Page not found" />
    <div class="grid justify-items-center h-screen content-center text-center">
        <div>
          <h1 class="text-4xl">404 / Page not found</h1>
          <div class="py-4">
            I really sorry but I can't find the page
          </div>
        </div>
      </div>
  </Layout>
</template>
