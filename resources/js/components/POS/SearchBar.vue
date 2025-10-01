<template>
  <div class="input-group input-group-lg">
    <span class="input-group-text">
      <i class="bi bi-search"></i>
    </span>
    <input 
      type="text" 
      class="form-control" 
      placeholder="Search products by name or barcode..."
      v-model="searchQuery"
      @input="handleSearch"
    >
    <button 
      v-if="searchQuery" 
      class="btn btn-outline-secondary" 
      type="button"
      @click="clearSearch"
    >
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
  data() {
    return {
      searchQuery: '',
      debounceTimer: null
    }
  },
  methods: {
    handleSearch() {
      clearTimeout(this.debounceTimer)
      this.debounceTimer = setTimeout(() => {
        this.$emit('search', this.searchQuery)
      }, 500)
    },
    clearSearch() {
      this.searchQuery = ''
      this.$emit('search', '')
    }
  }
}
</script>