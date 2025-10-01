<template>
  <div 
    class="modal fade" 
    tabindex="-1" 
    ref="modal"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" :class="danger ? 'bg-danger text-white' : ''">
          <h5 class="modal-title">{{ title }}</h5>
          <button 
            type="button" 
            class="btn-close" 
            :class="{ 'btn-close-white': danger }"
            @click="handleCancel"
          ></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">{{ message }}</p>
        </div>
        <div class="modal-footer">
          <button 
            type="button" 
            class="btn btn-secondary" 
            @click="handleCancel"
          >
            {{ cancelText }}
          </button>
          <button 
            type="button" 
            class="btn"
            :class="danger ? 'btn-danger' : 'btn-primary'"
            @click="handleConfirm"
          >
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from 'bootstrap';

export default {
  name: 'ConfirmModal',
  props: {
    title: {
      type: String,
      default: 'Confirm Action'
    },
    message: {
      type: String,
      required: true
    },
    confirmText: {
      type: String,
      default: 'Confirm'
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    danger: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      modalInstance: null
    };
  },
  mounted() {
    this.modalInstance = new Modal(this.$refs.modal);
  },
  methods: {
    show() {
      this.modalInstance.show();
    },
    hide() {
      this.modalInstance.hide();
    },
    handleConfirm() {
      this.$emit('confirm');
      this.hide();
    },
    handleCancel() {
      this.$emit('cancel');
      this.hide();
    }
  }
}
</script>