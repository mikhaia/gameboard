<script setup>
import { watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { showToast, toastMessage, toastType, toastVisible } from '../../toast'

const page = usePage()

watch(
  () => page.props.toast,
  (toast) => {
    if (toast?.success) {
      showToast(toast.success, 'success')
    }

    if (toast?.error) {
      showToast(toast.error, 'error')
    }
  },
  { immediate: true, deep: true }
)
</script>
<template>
    <div
        :class="{'opacity-0': !toastVisible, 'opacity-100': toastVisible, 'z-0': !toastVisible}"
        class="toast"
        v-bind:class="toastType"
        v-html="toastMessage"
      ></div>
</template>
