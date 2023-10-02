<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import boardModal from './Modals/Board.vue'

const page = usePage()

const openModal = (data) => {
  boardModal.methods.open(data)
}
</script>

<template>
<Toast></Toast>
<div class="h-screen">
  <aside class="bg-gray-800 z-10 h-screen sidebar">
      <h1 class="text-white text-2xl font-bold text-center py-4">{{ page.props.appName }}</h1>
      <nav class="sidenav">
        <li>
          <a @click="openModal({title: 'New Board'})" class="cursor-pointer" id="create-board">
            <span class="icon"><i class="gg-add-r"></i></span>
            <span class="name">Create New Board</span>
          </a>
        </li>
        <li :class="{'active' : $page.url.startsWith('/boards/'+item.id) }"
          v-for="item in page.props.boards">
          <button class="edit-board-btn" @click="openModal(item)">⚙️</button>
          <Link :href="'/boards/'+item.id">
            <img :src="item.icon" class="icon"/>
            <span class="name">{{ item.title }}</span>
          </Link>
        </li>
        <li :class="{'active' : $page.component === 'Index' }">
          <Link href="/">
            <span class="icon"><i class="gg-board"></i></span>
            <span class="name">Dashboard</span>
          </Link>
        </li>
      </nav>
    </aside>
    
    <main class="relative main">
      <div class="user-panel">
        <img v-if="page.props.user.avatar" :src="page.props.user.avatar" class="avatar" />
        <img v-else :src="'/data/avatar/avatar.jpg'" class="avatar">
        {{ page.props.user.name }}
        <Link href="/logout" class="exit">x</Link>
      </div>
      <slot />
      <boardModal></boardModal>
    </main>
</div>
</template>
