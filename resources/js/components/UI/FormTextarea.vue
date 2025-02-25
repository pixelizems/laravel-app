<template>
  <div class="mb-3">
    <label :for="id" class="form-label">{{ label }}</label>
    <textarea 
      :id="id" 
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :class="{ 'is-invalid': error }"
      class="form-control"
      v-bind="$attrs"
    ></textarea>
    <div v-if="error" class="invalid-feedback">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: 'FormTextarea',
  props: {
    id: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    modelValue: {
      type: String,
      default: ''
    },
    error: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue']
}
</script> 