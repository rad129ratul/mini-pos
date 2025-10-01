<template>
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Product Management</h2>
      <button class="btn btn-primary btn-lg" @click="openCreateModal">
        <i class="bi bi-plus-circle me-2"></i>
        Add Product
      </button>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <ProductList 
          @edit="openEditModal" 
          @delete="handleDelete"
          ref="productList"
        />
      </div>
    </div>

    <ProductForm 
      ref="productForm"
      :product="selectedProduct"
      @saved="handleSaved"
    />

    <ConfirmModal
      ref="deleteModal"
      title="Delete Product"
      message="Are you sure you want to delete this product? This action cannot be undone."
      confirm-text="Delete"
      cancel-text="Cancel"
      :danger="true"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script>
import ProductList from '../components/Products/ProductList.vue'
import ProductForm from '../components/Products/ProductForm.vue'
import ConfirmModal from '../components/Common/ConfirmModal.vue'
import productService from '../services/productService'
import { useToastStore } from '../store/toast'

export default {
  name: 'Products',
  components: {
    ProductList,
    ProductForm,
    ConfirmModal
  },
  data() {
    return {
      selectedProduct: null,
      productToDelete: null
    }
  },
  methods: {
    openCreateModal() {
      this.selectedProduct = null
      this.$refs.productForm.show()
    },
    openEditModal(product) {
      this.selectedProduct = product
      this.$refs.productForm.show()
    },
    handleDelete(product) {
      this.productToDelete = product
      this.$refs.deleteModal.show()
    },
    async confirmDelete() {
      try {
        await productService.delete(this.productToDelete.id)
        useToastStore().success('Product deleted successfully')
        this.$refs.productList.fetchProducts()
      } catch (error) {
        useToastStore().error('Failed to delete product')
      }
    },
    handleSaved() {
      this.$refs.productList.fetchProducts()
    }
  }
}
</script>