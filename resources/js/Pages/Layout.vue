<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import boardModal from './Modals/Board.vue'
import profileModal from './Modals/Profile.vue'
import passwordModal from './Modals/Password.vue'

const page = usePage()

const openModal = (data) => {
  boardModal.methods.open(data)
}

const openProfileModal = () => {
  profileModal.methods.open();
}

const openPasswordModal = () => {
  passwordModal.methods.open();
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
            <span class="material-symbols-outlined icon">add_circle</span>
            <span class="name">Create New Board</span>
          </a>
        </li>
        <li :class="{'active' : $page.url.startsWith('/boards/'+item.id) }"
          v-for="item in page.props.boards">
          <button class="edit-board-btn" @click="openModal(item)">⚙️</button>
          <Link :href="'/boards/'+item.id">
            <img v-if="item.icon" :src="item.icon" class="icon" />
            <img v-else :src="'/data/icon/0.png'" class="icon"/>
            <span class="name">{{ item.title }}</span>
          </Link>
        </li>
        <li :class="{'active' : $page.component === 'Index' }">
          <Link href="/">
            <span class="material-symbols-outlined icon">dashboard</span>
            <span class="name">Dashboard</span>
          </Link>
        </li>
      </nav>
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
            <a @click="openProfileModal()" class="block px-4 py-2 text-sm">Profile settigns</a>
            <a @click="openPasswordModal()" class="block px-4 py-2 text-sm">Change password</a>
            <Link href="/logout" class="block px-4 py-2 text-sm">Logout</Link>
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
