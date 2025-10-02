<template>
  <div>
    <Navbar />
    <div class="d-flex">
      <Sidebar class="d-none d-md-block" />
      <main class="flex-grow-1 p-4 main-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'

export default {
  name: 'AppLayout',
  components: {
    Navbar,
    Sidebar
  }
}
</script>

<style scoped>
.main-content {
  margin-left: 250px;
  margin-top: 56px;
  min-height: calc(100vh - 56px);
}

@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
  }
}
</style>