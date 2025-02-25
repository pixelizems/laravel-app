<template>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form @submit.prevent="submitForm">
          <Alert 
            :message="successMessage" 
            type="success" 
          />
          
          <Alert 
            :message="errorMessage" 
            type="danger" 
          />

          <FormInput 
            id="name"
            label="Name"
            v-model="form.name"
            :error="errors.name ? errors.name[0] : ''"
            maxlength="10"
          />

          <FormInput 
            id="email"
            label="Email"
            type="email"
            v-model="form.email"
            :error="errors.email ? errors.email[0] : ''"
          />

          <FormInput 
            id="phone"
            label="Phone"
            type="tel"
            v-model="form.phone"
            :error="errors.phone ? errors.phone[0] : ''"
            v-mask="'+1##########'"
          />

          <FormTextarea 
            id="message"
            label="Message"
            v-model="form.message"
            :error="errors.message ? errors.message[0] : ''"
            rows="4"
          />

          <FormInput 
            id="street"
            label="Street"
            v-model="form.street"
            :error="errors.street ? errors.street[0] : ''"
          />

          <FormInput 
            id="state"
            label="State"
            v-model="form.state"
            :error="errors.state ? errors.state[0] : ''"
          />

          <FormInput 
            id="zip"
            label="ZIP"
            v-model="form.zip"
            :error="errors.zip ? errors.zip[0] : ''"
          />

          <FormInput 
            id="country"
            label="Country"
            v-model="form.country"
            :error="errors.country ? errors.country[0] : ''"
          />

          <FormFileUpload 
            id="images"
            label="Images (JPG only)"
            :error="errors.images ? errors.images[0] : ''"
            accept=".jpg,.jpeg"
            multiple
            @file-change="handleImageUpload"
          />

          <FormFileUpload 
            id="files"
            label="Files (PDF only)"
            :error="errors.files ? errors.files[0] : ''"
            accept=".pdf"
            multiple
            @file-change="handleFileUpload"
          />

          <button 
            type="submit" 
            :disabled="isSubmitting"
            class="btn btn-primary"
          >
            {{ isSubmitting ? 'Submitting...' : 'Submit' }}
          </button>
        </form>
      </div>
    </div>
</template>

<script>
import FormInput from './UI/FormInput.vue';
import FormTextarea from './UI/FormTextarea.vue';
import FormFileUpload from './UI/FormFileUpload.vue';
import Alert from './UI/Alert.vue';
import ContactService from '../services/ContactService';
import { mask } from 'vue-the-mask';

export default {
  name: 'ContactForm',
  components: {
    FormInput,
    FormTextarea,
    FormFileUpload,
    Alert
  },
  directives: {
    mask
  },
  
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        message: '',
        street: '',
        state: '',
        zip: '',
        country: '',
        images: [],
        files: []
      },
      errors: {},
      isSubmitting: false,
      successMessage: '',
      errorMessage: ''
    }
  },

  watch: {
    'form.email': function(newValue) {
      if (newValue && newValue.toLowerCase().includes('@gmail.com')) {
        if (!this.errors.email) {
          this.errors.email = [];
        }
        this.errors.email = ['Gmail addresses are not allowed'];
      } else if (this.errors.email && this.errors.email[0] === 'Gmail addresses are not allowed') {
        this.errors.email = [];
      }
    }
  },

  methods: {
    handleImageUpload(files) {
      this.form.images = files;
    },

    handleFileUpload(files) {
      this.form.files = files;
    },

    async submitForm() {
      this.isSubmitting = true;
      this.errors = {};
      this.successMessage = '';
      this.errorMessage = '';

      // Check for Gmail address before submission
      if (this.form.email.toLowerCase().includes('@gmail.com')) {
        this.errors.email = ['Gmail addresses are not allowed'];
        this.isSubmitting = false;
        return;
      }

      const formData = new FormData();
      
      // Append text fields
      Object.keys(this.form).forEach(key => {
        if (!['images', 'files'].includes(key)) {
          formData.append(key, this.form[key]);
        }
      });

      // Append files
      this.form.images.forEach(image => {
        formData.append('images[]', image);
      });

      this.form.files.forEach(file => {
        formData.append('files[]', file);
      });

      try {
        const response = await ContactService.submitForm(formData);
        this.successMessage = 'Your message has been sent successfully!';
        this.resetForm();
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        } else {
          this.errorMessage = 'An error occurred while submitting the form. Please try again.';
        }
      } finally {
        this.isSubmitting = false;
      }
    },

    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        message: '',
        street: '',
        state: '',
        zip: '',
        country: '',
        images: [],
        files: []
      };
      // Reset file inputs
      document.getElementById('images').value = '';
      document.getElementById('files').value = '';
    }
  }
}
</script>