import { ref } from 'vue'

export const toastVisible = ref(false)
export const toastMessage = ref('')
export const toastType = ref('error')

let hideTimeoutId

export function showToast(message, type = 'error') {
  if (!message) {
    return
  }

  toastVisible.value = true
  toastMessage.value = message
  toastType.value = type

  clearTimeout(hideTimeoutId)
  hideTimeoutId = setTimeout(() => {
    toastVisible.value = false
  }, 3000)
}
