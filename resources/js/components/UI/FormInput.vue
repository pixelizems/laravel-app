<template>
  <div class="mb-3">
    <label :for="id" class="form-label">{{ label }}</label>
    <input 
      :type="type" 
      :id="id" 
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :class="{ 'is-invalid': error }"
      class="form-control"
      v-bind="$attrs"
    >
    <div v-if="error" class="invalid-feedback">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: 'FormInput',
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
      type: [String, Number],
      default: ''
    },
    type: {
      type: String,
      default: 'text'
    },
    error: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue']
}
</script> 