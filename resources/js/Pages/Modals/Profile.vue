<script>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { showToast } from '../../toast';
  let data = ref();
  let title = ref('Profile settigns');
  let form = ref();
  let errors = ref();
  const page = usePage();

  let isShow = ref(false);
  export default {
    name: 'profileModal',
    setup() {
      return {data, title, form, errors, isShow};
    },
    methods: {
      open() {
        const formData = {
          name: page.props.user.name,
          email: page.props.user.email,
          avatar: page.props.user.avatar,
          _method: 'PUT'
        };

        form = useForm(formData);
        isShow.value = true;
      },
      close() {
        isShow.value = false;
      },
      submit() {
        form.post('/profile', {
            forceFormData: true,
            onError: (err) => {
                errors.value = validations(err);
            },
            onSuccess: (data) => {
              errors.value = false;
              showToast(data.props.toast.success, 'success');
              this.close();
            }
          })
      },
      image(event) {
        changeCover(event.target.files);
      },
    }
  };

function validations(errors) {
  let result = '<ul>';
  for(var i in errors)
    result += '<li>' + errors[i] + '</li>';
  result += '</ul>';
  return result;
}

function changeCover(files) {
  const file = files[0];
  if (!file) return;
  if (file.type === 'image/png' || file.type === 'image/jpeg') {
    form.avatar = file;
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
      document.getElementById('avatar').previousSibling.src = reader.result;
    }
  }
}

function getImage(url) {
  return '/getimage?url=' + url;
}

window.addEventListener('paste', event => {
  if (data.value) {
    const url = event.clipboardData.getData("text");
    if (url.startsWith('http://') || url.startsWith('https://')) {
      const img = getImage(url);
      axios(img).then(function(response) {
        if (response.data) {
          form.avatar = img;
        }
      });

    } else if (event.clipboardData.files) {
      changeCover(event.clipboardData.files);
    }
  }
})
</script>

<script setup>
//
</script>

<template>
  <div v-if="isShow" class="modal">
    <div class="glass container">
      <div class="modal-header">
        <a class="btn-close" @click="close()"></a>
        <h4 class="title">{{ title }}</h4>
      </div>
      <div class="modal-content">
        <form class="form" @submit.prevent="submit">
            <div class="msg-error" v-if="errors" v-html="errors"></div>
            <div class="form-input pt-5 pb-2.5">
                <input type="text" v-model="form.name" id="name" placeholder="Name">
                <label for="name">Name</label>
            </div>
            <div class="form-input pt-5 pb-2.5">
                <input type="email" v-model="form.email" id="email" placeholder="E-mail">
                <label for="email">E-mail</label>
            </div>
            <div class="form-file pt-2.5">
                <label for="avatar">
                    Avatar (Click here or Ctrl+V to change)
                    <img v-bind:src="form.avatar">
                    <input type="file"
                    id="avatar"
                    @input="form.avatar = $event.target.files[0]"
                    @change="image($event)">
                </label>
            </div>
            <div class="pt-4">
                <button type="submit" class="button w-full">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</template>
