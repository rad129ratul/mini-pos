<template>
  <div class="card h-100 shadow-sm" :style="{ borderLeft: `4px solid ${color}` }">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-muted mb-2">{{ title }}</h6>
          <h2 class="mb-0 fw-bold">{{ formattedValue }}</h2>
        </div>
        <div>
          <i :class="`bi ${icon} fs-1`" :style="{ color: color }"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatsCard',
  props: {
    title: {
      type: String,
      required: true
    },
    value: {
      type: [Number, String],
      required: true
    },
    icon: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: '#0d6efd'
    },
    prefix: {
      type: String,
      default: ''
    }
  },
  computed: {
    formattedValue() {
      if (this.prefix) {
        return `${this.prefix}${this.formatNumber(this.value)}`;
      }
      return this.formatNumber(this.value);
    }
  },
  methods: {
    formatNumber(num) {
      if (typeof num === 'number') {
        return num.toLocaleString();
      }
      return num;
    }
  }
}
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-5px);
}
</style>