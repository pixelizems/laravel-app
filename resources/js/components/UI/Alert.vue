<template>
  <div 
    v-if="message" 
    class="alert" 
    :class="alertClass" 
    role="alert"
  >
    {{ message }}
  </div>
</template>

<script>
export default {
  name: 'Alert',
  props: {
    message: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'info',
      validator: (value) => ['success', 'danger', 'warning', 'info'].includes(value)
    }
  },
  computed: {
    alertClass() {
      return `alert-${this.type}`;
    }
  }
}
</script> 