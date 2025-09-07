<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import boardModal from './Modals/Board.vue'
import profileModal from './Modals/Profile.vue'
import passwordModal from './Modals/Password.vue'
import { onMounted, ref } from 'vue'
import Sortable from 'sortablejs'
import axios from 'axios'

const page = usePage()
const boards = ref(page.props.boards)

const openModal = (data) => {
  boardModal.methods.open(data)
}

const openProfileModal = () => {
  profileModal.methods.open();
}

const openPasswordModal = () => {
  passwordModal.methods.open();
}

onMounted(() => {
  Sortable.create(document.getElementById('boards-list'), {
    animation: 150,
    ghostClass: 'board-placeholder',
    draggable: '.board-item',
    onEnd: () => {
      const order = Array.from(document.querySelectorAll('#boards-list .board-item'))
        .map(el => el.dataset.id)
      boards.value = order.map(id => boards.value.find(b => b.id === id))
      axios.put('/boards/sort', order)
    },
    onMove: (evt) => {
      const related = evt.related
      if (!related) return true
      if (related.id === 'create-board' || related.id === 'dashboard') return false
      return true
    }
  })
})
</script>

<template>
<Toast></Toast>
<div class="h-screen">
  <aside class="bg-gray-800 z-10 h-screen sidebar">
      <h1 class="text-white text-2xl font-bold text-center py-4">{{ page.props.appName }}</h1>
      <ul class="sidenav" id="boards-list">
        <li id="create-board">
          <a @click="openModal({title: 'New Board'})" class="cursor-pointer">
            <span class="material-symbols-outlined icon">add_circle</span>
            <span class="name">Create New Board</span>
          </a>
        </li>
        <li v-for="item in boards" :key="item.id" :data-id="item.id" class="board-item"
          :class="{'active' : $page.url.startsWith('/boards/'+item.id) }">
          <button class="edit-board-btn"
            :class="{ 'text-white': !$page.url.startsWith('/boards/' + item.id) }"
            @click="openModal(item)">
            <span class="material-symbols-outlined">settings</span>
          </button>
          <Link :href="'/boards/'+item.id">
            <img v-if="item.icon" :src="item.icon" class="icon" />
            <img v-else :src="'/img/board.png'" class="icon"/>
            <span class="name">{{ item.title }}</span>
          </Link>
        </li>
        <li id="dashboard" :class="{'active' : $page.component === 'Index' }">
          <Link href="/">
            <span class="material-symbols-outlined icon">dashboard</span>
            <span class="name">Dashboard</span>
          </Link>
        </li>
      </ul>
    </aside>

    <main class="relative main">
      <div class="user-panel">
        <div class="user-info">
          <img v-if="page.props.user.avatar" :src="page.props.user.avatar" class="avatar" />
          <img v-else :src="'/img/avatar.png'" class="avatar">
          {{ page.props.user.name }}
          <Link href="/logout" class="exit">x</Link>
        </div>
        <div class="user-dropdown right-0 z-10 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
          <div class="py-1">
            <a @click="openProfileModal()" class="cursor-pointer flex items-center px-4 py-2 text-sm">
              <span class="material-symbols-outlined mr-2">manage_accounts</span>
              <span>Profile settings</span>
            </a>
            <a @click="openPasswordModal()" class="cursor-pointer flex items-center px-4 py-2 text-sm">
              <span class="material-symbols-outlined mr-2">lock_reset</span>
              <span>Change password</span>
            </a>
            <Link href="/logout" class="cursor-pointer flex items-center px-4 py-2 text-sm">
              <span class="material-symbols-outlined mr-2">logout</span>
              <span>Logout</span>
            </Link>
          </div>
        </div>
      </div>
      <slot />
      <boardModal></boardModal>
      <profileModal></profileModal>
      <passwordModal></passwordModal>
    </main>
</div>
</template>
