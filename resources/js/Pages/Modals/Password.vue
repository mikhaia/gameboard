<script>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { showToast } from '../../toast';
  let data = ref();
  let title = ref('Change password');
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
          old_password: '',
          new_password: '',
          new_password_confirmation: '',
        };

        form = useForm(formData);
        isShow.value = true;
      },
      close() {
        isShow.value = false;
      },
      submit() {
        form.post('/password', {
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
                <input type="password" v-model="form.old_password" id="old_password" placeholder="Old password">
                <label for="old_password">Old password</label>
            </div>
            <div class="form-input pt-5 pb-2.5">
                <input type="password" v-model="form.new_password" id="new_password" placeholder="New password">
                <label for="new_password">New password</label>
            </div>
            <div class="form-input pt-5 pb-2.5">
                <input type="password" v-model="form.new_password_confirmation" id="new_password_confirmation" placeholder="Repeat new password">
                <label for="new_password_confirmation">Repeat new password</label>
            </div>
            <div class="pt-4">
                <button type="submit" class="button w-full">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</template>
