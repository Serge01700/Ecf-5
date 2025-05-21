<template>
  <div class="stat-card">
    <div class="icon">
      <!-- <slot name="icon" /> -->
    </div>
    <div class="stat-content">
      <h3 class="title">{{ title }}</h3>
      <p class="value">{{ value }}</p>
      <p class="change" :class="{ positive: isPositive, negative: !isPositive }">
        {{ change }} <span class="compare-text">{{ compareText }}</span>
      </p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  change: String,
  compareText: String,
  isPositive: Boolean
});
</script>

<style scoped>
.stat-card {
  display: flex;
  gap: 1rem;
  padding: 1.2rem;
  border-radius: 12px;
  background-color: white;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  align-items: center;
}
.icon {
  background-color: #f2f2f2;
  border-radius: 50%;
  padding: 0.6rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.stat-content .title {
  font-size: 0.9rem;
  color: #555;
}
.stat-content .value {
  font-size: 1.6rem;
  font-weight: bold;
}
.stat-content .change {
  font-size: 0.85rem;
  margin-top: 0.2rem;
}
.positive {
  color: #16a34a;
}
.negative {
  color: #dc2626;
}
.compare-text {
  font-weight: 400;
  margin-left: 0.3rem;
}
</style>
