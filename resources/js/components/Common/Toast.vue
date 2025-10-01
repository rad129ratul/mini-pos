<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <transition-group name="toast">
      <div
        v-for="toast in toastStore.messages"
        :key="toast.id"
        class="toast show"
        role="alert"
      >
        <div 
          class="toast-header text-white"
          :class="toast.type === 'success' ? 'bg-success' : 'bg-danger'"
        >
          <i 
            class="bi me-2"
            :class="toast.type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'"
          ></i>
          <strong class="me-auto">{{ toast.type === 'success' ? 'Success' : 'Error' }}</strong>
          <button 
            type="button" 
            class="btn-close btn-close-white" 
            @click="toastStore.remove(toast.id)"
          ></button>
        </div>
        <div class="toast-body">
          {{ toast.message }}
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
import { useToastStore } from '../../store/toast';

export default {
  name: 'Toast',
  setup() {
    const toastStore = useToastStore();
    return { toastStore };
  }
}
</script>

<style scoped>
.toast {
  min-width: 300px;
  margin-bottom: 0.5rem;
}

.toast-enter-active {
  animation: slideIn 0.3s ease-out;
}

.toast-leave-active {
  animation: slideOut 0.3s ease-in;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}
</style>