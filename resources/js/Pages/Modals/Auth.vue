<script>
  import { ref } from 'vue';
  let show = ref();
  let title = ref('Sign In');
  let error = ref();
  let isPasswordShowing = ref(true);
  let action = ref('signin');

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

function submit() {
  form.post('/'+action.value, {
    onError: (errors) => {
      error.value = validations(errors);
    }
  });
}

function validations(errors) {
  let result = '<ul>';
  for(var i in errors)
    result += '<li>' + errors[i] + '</li>';
  result += '</ul>';
  return result;
}

function signin() {
  action.value = 'signin';
  title.value = 'Sign In';
  isPasswordShowing.value = true;
}

function signup() {
  action.value = 'signup';
  isPasswordShowing.value = true;
  title.value = 'Sign Up';
}

function recovery() {
  action.value = 'recovery';
  title.value = 'Recovery Access';
  isPasswordShowing.value = false;
  // TODO: Add recover calls
}
</script>

<template>
    <div v-if="show" class="modal">
      <div class="glass container">
        <div class="modal-header">
          <a class="btn-close" @click="close()"></a>
          <h4 class="title">{{ title }} Form</h4>
        </div>
        <div class="modal-content">
          <form class="form" @submit.prevent="submit()">
            <div class="msg-error" v-if="error" v-html="error"></div>
            <div class="form-input pt-5 pb-2.5">
              <input type="email" v-model="form.email" id="email" placeholder="Email">
              <label for="email">E-mail</label>
            </div>
            <div class="form-input pt-5 pb-2.5" v-if="isPasswordShowing">
              <input type="password" v-model="form.password" id="password" placeholder="Password">
              <label for="password">Password</label>
            </div>
            <div class="pt-2">
              <button type="submit" class="button w-full">{{ title }}</button>
            </div>
            <div class="flex gap-3 pt-3">
              <button type="button" class="button secondary w-full" @click="signin()" v-if="action != 'signin'">Sign In</button>
              <button type="button" class="button secondary w-full" @click="signup()" v-if="action != 'signup'">Sign Up</button>
              <button type="button" class="button secondary w-full" @click="recovery()" v-if="action != 'recovery'">Recovery</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
