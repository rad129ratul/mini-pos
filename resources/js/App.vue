<template>
  <AppLayout />
  <Toast />
</template>

<script>
import AppLayout from './components/Layout/AppLayout.vue'
import Toast from './components/Common/Toast.vue'

export default {
  name: 'App',
  components: {
    AppLayout,
    Toast
  }
}
</script>