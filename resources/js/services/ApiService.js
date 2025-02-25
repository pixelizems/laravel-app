import axios from 'axios';

class ApiService {
  constructor() {
    this.axios = axios.create({
      baseURL: '/api',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      withCredentials: true
    });

    // Add CSRF token to every request
    this.axios.interceptors.request.use(config => {
      // Get the CSRF token from the meta tag
      const token = document.head.querySelector('meta[name="csrf-token"]');
      
      if (token) {
        config.headers['X-CSRF-TOKEN'] = token.content;
      }
      
      return config;
    });
  }

  /**
   * Submit contact form data
   * 
   * @param {FormData} formData - The form data to submit
   * @returns {Promise} - The axios promise
   */
  submitContactForm(formData) {
    return this.axios.post('/contact-submissions', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  /**
   * Generic GET request
   * 
   * @param {string} url - The URL to request
   * @param {Object} params - Query parameters
   * @returns {Promise} - The axios promise
   */
  get(url, params = {}) {
    return this.axios.get(url, { params });
  }

  /**
   * Generic POST request
   * 
   * @param {string} url - The URL to post to
   * @param {Object} data - The data to send
   * @returns {Promise} - The axios promise
   */
  post(url, data = {}) {
    return this.axios.post(url, data);
  }

  /**
   * Generic PUT request
   * 
   * @param {string} url - The URL to put to
   * @param {Object} data - The data to send
   * @returns {Promise} - The axios promise
   */
  put(url, data = {}) {
    return this.axios.put(url, data);
  }

  /**
   * Generic DELETE request
   * 
   * @param {string} url - The URL to delete
   * @returns {Promise} - The axios promise
   */
  delete(url) {
    return this.axios.delete(url);
  }
}

export default new ApiService(); 