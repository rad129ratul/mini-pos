<template>
  <div class="text-center py-4">
    <div 
      class="spinner-border text-primary" 
      :class="spinnerClass"
      role="status"
    >
      <span class="visually-hidden">Loading...</span>
    </div>
    <p v-if="text" class="mt-2 text-muted" :class="textClass">{{ text }}</p>
  </div>
</template>

<script>
export default {
  name: 'LoadingSpinner',
  props: {
    text: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    }
  },
  computed: {
    spinnerClass() {
      return {
        'spinner-border-sm': this.size === 'sm',
        'spinner-border-lg': this.size === 'lg'
      };
    },
    textClass() {
      return {
        'small': this.size === 'sm',
        'fs-5': this.size === 'lg'
      };
    }
  }
}
</script>

<style scoped>
.spinner-border-lg {
  width: 3rem;
  height: 3rem;
  border-width: 0.3em;
}
</style>