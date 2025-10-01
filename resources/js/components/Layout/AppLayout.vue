<template>
  <div>
    <Navbar />
    <div class="d-flex">
      <Sidebar class="d-none d-md-block" />
      <main class="flex-grow-1 p-4" :class="{ 'with-sidebar': true }">
        <router-view />
      </main>
    </div>
    <Toast />
  </div>
</template>

<script>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import Toast from '../Common/Toast.vue'

export default {
  name: 'AppLayout',
  components: {
    Navbar,
    Sidebar,
    Toast
  }
}
</script>

<style scoped>
.with-sidebar {
  margin-left: 250px;
  margin-top: 56px;
}

@media (max-width: 768px) {
  .with-sidebar {
    margin-left: 0;
  }
}
</style>