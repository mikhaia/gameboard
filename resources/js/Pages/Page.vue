<script setup>
import Layout from './Layout.vue'
import { Head } from '@inertiajs/vue3'
import showdown from 'showdown'

defineProps({ title: String, content: String, page: String })

const converter = new showdown.Converter();
converter.setOption('simpleLineBreaks', true);

function toHtml(text) {
  return converter.makeHtml(text);
}
</script>

<template>
  <Layout>
    <Head :title="title" />
    <div class="grid justify-items-center h-screen content-center">
        <div>
          <h1>{{ title }}</h1>
          <div v-html="toHtml(content)"></div>
          <code class="block whitespace-pre border-dotted border-2 p-4" v-text="page"></code>
        </div>
      </div>
  </Layout>
</template>
