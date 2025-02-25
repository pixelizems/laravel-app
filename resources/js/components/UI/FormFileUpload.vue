<template>
  <div class="mb-3">
    <label :for="id" class="form-label">{{ label }}</label>
    <input 
      type="file" 
      :id="id" 
      @change="handleFileChange"
      :class="{ 'is-invalid': error }"
      class="form-control"
      v-bind="$attrs"
    >
    <div v-if="error" class="invalid-feedback">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: 'FormFileUpload',
  props: {
    id: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    error: {
      type: String,
      default: ''
    }
  },
  emits: ['file-change'],
  methods: {
    handleFileChange(event) {
      const files = Array.from(event.target.files);
      this.$emit('file-change', files);
    }
  }
}
</script> 