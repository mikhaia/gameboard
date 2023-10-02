<script>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import Toast from '../Inc/Toast.vue';
  let data = ref();
  let title = ref();
  let form = ref();
  let errors = ref();
  let positions = ref();
  const focus = ref();
  const page = usePage();
  export default {
    name: 'columnModal',
    setup() {
      return {data, title};
    },
    methods: {
      open(value, boardId = null) {
        const formData = {
          title: value.title,
          position: value.position,
          board_id: value.board_id || boardId
        };
        if (value.id) {
          formData._method = 'PUT';
        }

        positions = page.props.columns;
        if (!positions.length) {
          formData.position = -1;
        } else {
          formData.position = 0;
          for(let item in positions) {
            if (positions[item].position > formData.position)
              formData.position = positions[item].position;
          }
          formData.position++;
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
        let url = '/columns';
        if (data.value.id) url += '/' + data.value.id;
        form.post(url, {
            forceFormData: true,
            onError: (err) => {
              errors = err.msg;
            },
            onSuccess: () => {
              Toast.show('Column saved successfully', 'success');
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
            <input type="text" v-model="form.title" id="title" placeholder="Title" ref="focus">
            <label for="title">Title</label>
          </div>
          <div class="form-select">
            <select v-model="form.position" id="position">
              <option v-if="data.id" :value="data.position">Don't change</option>
              <option value="-1" v-if="!positions.length || data.id != positions[0].id">First</option>
              <template  v-for="item in positions">
                <option
                v-if="data.id != item.id"
                :key="item.id"
                :value="item.position + 1"
                >After: {{ item.title }}</option>
              </template>
            </select>
            <label for="position">Position</label>
          </div>
          <div>
            <button type="submit" class="button w-full">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
