<template>
  <div class="container-fluid py-4">
    <div class="row mb-4">
      <div class="col-md-6">
        <h2 class="fw-bold">Sales History</h2>
      </div>
      <div class="col-md-6">
        <div class="btn-group float-end" role="group">
          <button 
            type="button" 
            class="btn btn-outline-primary"
            :class="{ active: filter === 'today' }"
            @click="setFilter('today')"
          >
            Today
          </button>
          <button 
            type="button" 
            class="btn btn-outline-primary"
            :class="{ active: filter === 'week' }"
            @click="setFilter('week')"
          >
            This Week
          </button>
          <button 
            type="button" 
            class="btn btn-outline-primary"
            :class="{ active: filter === 'month' }"
            @click="setFilter('month')"
          >
            This Month
          </button>
          <button 
            type="button" 
            class="btn btn-outline-primary"
            :class="{ active: filter === 'all' }"
            @click="setFilter('all')"
          >
            All
          </button>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Loading sales...</p>
        </div>

        <div v-else-if="sales.length === 0" class="text-center py-5">
          <i class="bi bi-receipt fs-1 text-muted"></i>
          <p class="mt-3 text-muted">No sales found</p>
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Payment Method</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="sale in sales" 
                :key="sale.id"
                @click="viewDetails(sale)"
                style="cursor: pointer;"
              >
                <td>{{ formatDate(sale.created_at) }}</td>
                <td>{{ sale.customer_name || 'Walk-in Customer' }}</td>
                <td>
                  <span class="badge bg-secondary">{{ sale.items_count }} items</span>
                </td>
                <td class="fw-bold">৳{{ formatNumber(sale.total_amount) }}</td>
                <td>
                  <span 
                    class="badge" 
                    :class="sale.payment_method === 'cash' ? 'bg-success' : 'bg-primary'"
                  >
                    {{ sale.payment_method.toUpperCase() }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <nav v-if="pagination.last_page > 1" class="mt-3">
          <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
              <button class="page-link" @click="changePage(pagination.current_page - 1)">
                Previous
              </button>
            </li>
            <li 
              v-for="page in visiblePages" 
              :key="page"
              class="page-item"
              :class="{ active: page === pagination.current_page }"
            >
              <button class="page-link" @click="changePage(page)">{{ page }}</button>
            </li>
            <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
              <button class="page-link" @click="changePage(pagination.current_page + 1)">
                Next
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div 
      class="modal fade" 
      id="saleDetailsModal" 
      tabindex="-1" 
      ref="detailsModal"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sale Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" v-if="selectedSale">
            <div class="row mb-3">
              <div class="col-md-6">
                <p><strong>Date:</strong> {{ formatDate(selectedSale.created_at) }}</p>
                <p><strong>Customer:</strong> {{ selectedSale.customer_name || 'Walk-in Customer' }}</p>
              </div>
              <div class="col-md-6">
                <p><strong>Payment Method:</strong> 
                  <span 
                    class="badge" 
                    :class="selectedSale.payment_method === 'cash' ? 'bg-success' : 'bg-primary'"
                  >
                    {{ selectedSale.payment_method.toUpperCase() }}
                  </span>
                </p>
              </div>
            </div>

            <h6 class="mb-3">Items</h6>
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Unit Price</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in selectedSale.items" :key="item.id">
                  <td>{{ item.product.name }}</td>
                  <td>{{ item.quantity }}</td>
                  <td>৳{{ formatNumber(item.unit_price) }}</td>
                  <td>৳{{ formatNumber(item.subtotal) }}</td>
                </tr>
              </tbody>
            </table>

            <div class="row mt-4">
              <div class="col-md-6 offset-md-6">
                <table class="table table-sm">
                  <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-end">৳{{ formatNumber(selectedSale.subtotal) }}</td>
                  </tr>
                  <tr>
                    <td><strong>Tax (10%):</strong></td>
                    <td class="text-end">৳{{ formatNumber(selectedSale.tax) }}</td>
                  </tr>
                  <tr class="table-light">
                    <td><strong>Total:</strong></td>
                    <td class="text-end fw-bold">৳{{ formatNumber(selectedSale.total_amount) }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from 'bootstrap';
import saleService from '../services/saleService';

export default {
  name: 'Sales',
  data() {
    return {
      sales: [],
      loading: false,
      filter: 'all',
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
      },
      selectedSale: null,
      detailsModalInstance: null
    };
  },
  computed: {
    visiblePages() {
      const pages = [];
      const current = this.pagination.current_page;
      const last = this.pagination.last_page;
      
      for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
        pages.push(i);
      }
      return pages;
    }
  },
  mounted() {
    this.fetchSales();
    this.detailsModalInstance = new Modal(this.$refs.detailsModal);
  },
  methods: {
    async fetchSales(page = 1) {
      this.loading = true;
      try {
        const params = { page };
        if (this.filter !== 'all') {
          params.filter = this.filter;
        }
        
        const response = await saleService.getAll(params);
        this.sales = response.data;
        this.pagination = {
          current_page: response.current_page,
          last_page: response.last_page,
          per_page: response.per_page,
          total: response.total
        };
      } catch (error) {
        console.error('Error fetching sales:', error);
      } finally {
        this.loading = false;
      }
    },
    setFilter(filter) {
      this.filter = filter;
      this.fetchSales(1);
    },
    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.fetchSales(page);
      }
    },
    async viewDetails(sale) {
      try {
        const response = await saleService.getById(sale.id);
        this.selectedSale = response;
        this.detailsModalInstance.show();
      } catch (error) {
        console.error('Error fetching sale details:', error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    formatNumber(num) {
      return parseFloat(num).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
  }
};
</script>