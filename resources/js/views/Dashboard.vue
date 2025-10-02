<template>
  <div class="container-fluid py-4">
    <h2 class="fw-bold mb-4">Dashboard</h2>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2">Loading dashboard...</p>
    </div>

    <div v-else>
      <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
          <StatsCard
            title="Today's Sales"
            :value="stats.today_sales"
            icon="bi-currency-dollar"
            color="#28a745"
            prefix="৳"
          />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
          <StatsCard
            title="Today's Transactions"
            :value="stats.today_transactions"
            icon="bi-receipt"
            color="#0d6efd"
          />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
          <StatsCard
            title="Total Products"
            :value="stats.total_products"
            icon="bi-box-seam"
            color="#6f42c1"
          />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
          <StatsCard
            title="Low Stock Alerts"
            :value="stats.low_stock_count"
            icon="bi-exclamation-triangle"
            :color="stats.low_stock_count > 0 ? '#dc3545' : '#ffc107'"
          />
        </div>
      </div>

      <div class="row g-4">
        <div class="col-12 col-lg-7">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Recent Sales
              </h5>
            </div>
            <div class="card-body">
              <div v-if="stats.recent_sales && stats.recent_sales.length > 0" class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Amount</th>
                      <th>Payment</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="sale in stats.recent_sales" :key="sale.id">
                      <td>
                        <small>{{ formatDate(sale.date) }}</small>
                      </td>
                      <td>{{ sale.customer_name || 'Walk-in' }}</td>
                      <td class="fw-bold text-success">৳{{ formatNumber(sale.amount) }}</td>
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
              <div v-else class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">No sales yet</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-5">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-exclamation-circle me-2"></i>
                Low Stock Products
              </h5>
            </div>
            <div class="card-body">
              <div v-if="stats.low_stock_products && stats.low_stock_products.length > 0" class="table-responsive">
                <table class="table table-sm mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Product</th>
                      <th class="text-end">Stock</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in stats.low_stock_products" :key="product.id">
                      <td>{{ product.name }}</td>
                      <td class="text-end">
                        <span 
                          class="badge"
                          :class="{
                            'bg-danger': product.stock_quantity === 0,
                            'bg-warning': product.stock_quantity > 0
                          }"
                        >
                          {{ product.stock_quantity }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="text-center py-4 text-muted">
                <i class="bi bi-check-circle fs-1 text-success"></i>
                <p class="mt-2">All products in stock</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-2" v-if="stats.top_products && stats.top_products.length > 0">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-star me-2"></i>
                Top Selling Products (This Month)
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Product Name</th>
                      <th class="text-end">Total Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(product, index) in stats.top_products" :key="index">
                      <td>
                        <i class="bi bi-trophy-fill text-warning me-2" v-if="index === 0"></i>
                        {{ product.name }}
                      </td>
                      <td class="text-end">
                        <span class="badge bg-primary">{{ product.total_quantity }}</span>
                      </td>
                    </tr>
                  </tbody>
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
import StatsCard from '../components/Dashboard/StatsCard.vue';
import saleService from '../services/saleService';

export default {
  name: 'Dashboard',
  components: {
    StatsCard
  },
  data() {
    return {
      stats: {
        today_sales: 0,
        today_transactions: 0,
        total_products: 0,
        low_stock_count: 0,
        low_stock_products: [],
        recent_sales: [],
        top_products: []
      },
      loading: false,
      refreshInterval: null
    };
  },
  mounted() {
    this.fetchStats();
    this.refreshInterval = setInterval(() => {
      this.fetchStats();
    }, 30000);
  },
  beforeUnmount() {
    if (this.refreshInterval) {
      clearInterval(this.refreshInterval);
    }
  },
  methods: {
    async fetchStats() {
      this.loading = true;
      try {
        this.stats = await saleService.getDashboardStats();
      } catch (error) {
        console.error('Error fetching dashboard stats:', error);
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('en-US', {
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

<style scoped>
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-2px);
}

.table thead th {
  font-weight: 600;
  font-size: 0.9rem;
}
</style>