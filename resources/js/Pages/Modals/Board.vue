<script>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import Toast from '../Inc/Toast.vue';
  let data = ref();
  let title = ref();
  let displayDetails = ref();
  let form = ref();
  let focus = ref();
  let errors = ref();

  export default {
    name: 'boardModal',
    setup() {
      return {data, title, focus};
    },
    methods: {
      open(value) {
        const formData = {
          title: value.title,
          icon: value.icon,
          background: value.background,
          public: value.public || false,
          dark: value.dark || false,
        };
        if (value.id) {
          formData._method = 'PUT';
        }
        form = useForm(formData);
        data.value = value;
        title.value = value.title;
        setTimeout(() => focus.value.focus(), 100);
      },
      close() {
        data.value = null;
      },
      submit() {
        let url = '/boards';
        if (data.value.id) url += '/' + data.value.id;
        form.post(url, {
            forceFormData: true,
            onError: (err) => {
              errors = err.msg;
            },
            onSuccess: () => {
              Toast.show('Board saved successfully', 'success');
              this.close();
            }
          })
      },
      image(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
          event.target.previousSibling.src = reader.result;
        }
      }
    }
  };


</script>

<script setup>
// 
</script>

<template>
  <div v-if="data" class="modal">
    <div class="glass container">
      <div class="modal-header">
        <a class="btn-close" @click="close()"></a>
        <h4 class="title">{{ title }}</h4>
      </div>
      <div class="modal-content">
        <form class="form" @submit.prevent="submit">
          <div class="form-input pt-5 pb-2.5">
            <input type="text" v-model="form.title" id="title" placeholder="Title" ref="focus" autocomplete="off">
            <label for="title">Title</label>
          </div>
          <a class="cutter" @click="displayDetails = !displayDetails" :class="{expand: displayDetails}">
            <span class="icon">⏬</span> Details <span class="icon">⏬</span>
          </a>
          <div v-show="displayDetails">
            <div class="form-file py-2.5 form-icon">
              <label for="icon">
                <span>Icon</span>
                <img v-bind:src="form.icon" width="50" height="50" v-if="form.icon">
                <input type="file"
                  id="icon"
                  @input="form.icon = $event.target.files[0]"
                  @change="image($event)">
              </label>
            </div>
            <div class="form-file py-2.5">
              <label for="background">
                Background
                <img v-bind:src="form.background">
                <input type="file"
                  id="background"
                  @input="form.background = $event.target.files[0]"
                  @change="image($event)">
              </label>
            </div>
            <div class="flex">
              <div class="form-checkbox flex-1 pb-2.5">
                <label>
                  <input type="checkbox" v-model="form.public">
                  Public
                </label>
              </div>
              <div class="form-checkbox flex-1 pb-2.5">
                <label>
                  <input type="checkbox" v-model="form.dark">
                  Dark back
                </label>
              </div>
            </div>
          </div>
          <div>
            <button type="submit" class="button w-full">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
