<script>
  import { ref } from 'vue';
  let show = ref();

  export default {
    name: 'modalAuth',
    methods: {
      open() {
        show.value = true;
      },
      close() {
        show.value = false;
      }
    },
    open() {
      this.methods.open();
    }
  };
</script>

<script setup>
import { useForm } from '@inertiajs/vue3'
const form = useForm({
  email: null,
  password: null
})

let error = ref();

function submit() {
  form.post('/auth', {
    onError: (errors) => {
      error.value = errors.msg;
    }
  });
}
</script>

<template>
    <div v-if="show" class="modal">
      <div class="glass container">
        <div class="modal-header">
          <a class="btn-close" @click="close()"></a>
          <h4 class="title">Sign In</h4>
        </div>
        <div class="modal-content">
          <form class="form" @submit.prevent="submit()">
            <div class="msg-error" v-if="error">{{ error }}</div>
            <div class="form-input pt-5 pb-2.5">
              <input type="email" v-model="form.email" id="email" placeholder="Email">
              <label for="email">E-mail</label>
            </div>
            <div class="form-input pt-5 pb-2.5">
              <input type="password" v-model="form.password" id="password" placeholder="Password">
              <label for="password">Password</label>
            </div>
            <div class="pt-4">
              <button type="submit" class="button w-full">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
