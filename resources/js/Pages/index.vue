<script setup>
import Layout from './Layout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const props = defineProps({ background: String })

function createNewBoard() {
  document.getElementById('create-board').click();
}
</script>
 
<template>
  <Layout>
    <Head title="Welcome page" />
    <div class="dashboard" :style="{backgroundImage: `url(${props.background})`}">
      <h3>Your boards</h3>
      <ul>
        <li v-for="item in page.props.boards">
          <div class="card cursor-pointer">
            <Link :href="'/boards/'+item.id">
              <div class="back" :style="{backgroundImage: `url(${item.background})`}">
                <img class="icon" :src="item.icon" />
              </div>
              <h6 class="px-2 py-1 title w-full text-center">{{ item.title }}</h6>
            </Link>
          </div>
        </li>
        <li>
          <div class="card cursor-pointer" @click="createNewBoard()">
            <div class="back" :style="{backgroundImage: 'url(./img/dashboard/add-board.png)'}"></div>
            <h6 class="px-2 py-1 title w-full text-center">Create New</h6>
          </div>
        </li>
      </ul>

      <hr class="m-5">

      <h3>Project pages</h3>
      <ul>
        <li>
          <div class="card opacity-60">
            <div class="back" :style="{backgroundImage: 'url(./img/dashboard/templates.jpg)'}"></div>
            <h6 class="px-2 py-1 title w-full text-center color-gray">Templates [Not implemented yet]</h6>
          </div>
        </li>
        <li>
          <div class="card opacity-60">
            <div class="back" :style="{backgroundImage: 'url(./img/dashboard/bug-report.jpg)'}"></div>
            <h6 class="px-2 py-1 title w-full text-center">Bug report [Not implemented yet]</h6>
          </div>
        </li>
        <li>
          <div class="card cursor-pointer	">
            <Link href="/author">
              <div class="back" :style="{backgroundImage: 'url(./img/dashboard/author.jpg)'}"></div>
              <h6 class="px-2 py-1 title w-full text-center">Author</h6>
            </Link>
          </div>
        </li>
        <li>
          <div class="card cursor-pointer">
            <Link href="/about">
              <div class="back" :style="{backgroundImage: 'url(./img/dashboard/project.jpg)'}"></div>
              <h6 class="px-2 py-1 title w-full text-center">About</h6>
            </Link>
          </div>
        </li>
      </ul>
    </div>
  </Layout>
</template>
