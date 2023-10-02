<script>
  import { ref } from 'vue';
  let isShowing = ref();
  let message = ref();
  let type = ref('error');
  let show = ref();

  show = (msg, msgType = 'error') => {
      isShowing.value = true;
      message.value = msg;
      type.value = msgType;
      setTimeout(function(){
        isShowing.value = false;
      }, 3000);
  };

  export default {
    name: 'Toast',
    setup() {
      return {isShowing, message, type};
    },
    show(msg, type) {
      show(msg, type);
    }
  };
</script>
<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage()
if (page.props.toast.success) {
  show(page.props.toast.success, 'success');
}
if (page.props.toast.error) {
  show(page.props.toast.error, 'error');
}
</script>
<template>
    <div
        :class="{'opacity-0': !isShowing, 'opacity-100': isShowing, 'z-0': !isShowing}"
        class="toast"
        v-bind:class="type"
      >{{ message }}</div>
</template>
