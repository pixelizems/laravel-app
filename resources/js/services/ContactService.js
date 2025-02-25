import ApiService from './ApiService';

class ContactService {
  /**
   * Submit a contact form
   * 
   * @param {Object} formData - The form data to submit
   * @returns {Promise} - The API response
   */
  submitForm(formData) {
    return ApiService.submitContactForm(formData);
  }

  /**
   * Get contact form validation rules
   * 
   * @returns {Promise} - The API response with validation rules
   */
  getValidationRules() {
    return ApiService.get('/contact-validation-rules');
  }
}

export default new ContactService(); 