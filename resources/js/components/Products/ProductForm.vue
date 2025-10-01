<template>
  <div class="modal fade" ref="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ isEditMode ? 'Edit Product' : 'Add New Product' }}
          </h5>
          <button type="button" class="btn-close" @click="close"></button>
        </div>
        
        <form @submit.prevent="handleSubmit">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Name *</label>
                <input 
                  type="text" 
                  class="form-control" 
                  v-model="form.name"
                  :class="{ 'is-invalid': errors.name }"
                  required
                >
                <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Category *</label>
                <select 
                  class="form-select" 
                  v-model="form.category_id"
                  :class="{ 'is-invalid': errors.category_id }"
                  required
                >
                  <option value="">Select category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
                <div v-if="errors.category_id" class="invalid-feedback">{{ errors.category_id }}</div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Price (৳) *</label>
                <input 
                  type="number" 
                  class="form-control" 
                  v-model.number="form.price"
                  :class="{ 'is-invalid': errors.price }"
                  min="0"
                  step="0.01"
                  required
                >
                <div v-if="errors.price" class="invalid-feedback">{{ errors.price }}</div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Cost Price (৳)</label>
                <input 
                  type="number" 
                  class="form-control" 
                  v-model.number="form.cost_price"
                  :class="{ 'is-invalid': errors.cost_price }"
                  min="0"
                  step="0.01"
                >
                <div v-if="errors.cost_price" class="invalid-feedback">{{ errors.cost_price }}</div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Stock Quantity *</label>
                <input 
                  type="number" 
                  class="form-control" 
                  v-model.number="form.stock_quantity"
                  :class="{ 'is-invalid': errors.stock_quantity }"
                  min="0"
                  required
                >
                <div v-if="errors.stock_quantity" class="invalid-feedback">{{ errors.stock_quantity }}</div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Barcode</label>
                <input 
                  type="text" 
                  class="form-control" 
                  v-model="form.barcode"
                  :class="{ 'is-invalid': errors.barcode }"
                >
                <div v-if="errors.barcode" class="invalid-feedback">{{ errors.barcode }}</div>
              </div>

              <div class="col-12 mb-3">
                <label class="form-label">Image URL</label>
                <input 
                  type="text" 
                  class="form-control" 
                  v-model="form.image"
                  placeholder="https://example.com/image.jpg"
                >
              </div>

              <div class="col-12 mb-3">
                <label class="form-label">Description</label>
                <textarea 
                  class="form-control" 
                  v-model="form.description"
                  rows="3"
                ></textarea>
              </div>
            </div>

            <div v-if="error" class="alert alert-danger">
              {{ error }}
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="close" :disabled="loading">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Saving...
              </span>
              <span v-else>
                {{ isEditMode ? 'Update' : 'Create' }} Product
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from 'bootstrap';
import productService from '../../services/productService';
import categoryService from '../../services/categoryService';
import { useToastStore } from '../../store/toast';

export default {
  name: 'ProductForm',
  props: {
    product: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      modalInstance: null,
      categories: [],
      form: {
        name: '',
        category_id: '',
        price: 0,
        cost_price: 0,
        stock_quantity: 0,
        barcode: '',
        image: '',
        description: ''
      },
      errors: {},
      error: null,
      loading: false
    };
  },
  computed: {
    isEditMode() {
      return !!this.product;
    }
  },
  mounted() {
    this.modalInstance = new Modal(this.$refs.modal);
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        this.categories = await categoryService.getAll();
      } catch (err) {
        console.error('Failed to load categories:', err);
      }
    },
    show() {
      if (this.product) {
        this.form = {
          name: this.product.name,
          category_id: this.product.category.id,
          price: this.product.price,
          cost_price: this.product.cost_price || 0,
          stock_quantity: this.product.stock_quantity,
          barcode: this.product.barcode || '',
          image: this.product.image || '',
          description: this.product.description || ''
        };
      } else {
        this.resetForm();
      }
      this.errors = {};
      this.error = null;
      this.modalInstance.show();
    },
    close() {
      this.modalInstance.hide();
    },
    resetForm() {
      this.form = {
        name: '',
        category_id: '',
        price: 0,
        cost_price: 0,
        stock_quantity: 0,
        barcode: '',
        image: '',
        description: ''
      };
      this.errors = {};
      this.error = null;
    },
    async handleSubmit() {
      this.loading = true;
      this.errors = {};
      this.error = null;

      try {
        if (this.isEditMode) {
          await productService.update(this.product.id, this.form);
          useToastStore().success('Product updated successfully');
        } else {
          await productService.create(this.form);
          useToastStore().success('Product created successfully');
        }
        
        this.$emit('saved');
        this.close();
        this.resetForm();
      } catch (err) {
        if (err.response?.data?.errors) {
          this.errors = err.response.data.errors;
        } else {
          this.error = err.response?.data?.message || 'Failed to save product';
        }
      } finally {
        this.loading = false;
      }
    }
  },
  emits: ['saved']
};
</script>