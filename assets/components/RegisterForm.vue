<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input v-model="form.email" type="email" id="email" required
             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
    </div>

    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <input v-model="form.password" type="password" id="password" required
             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
    </div>

    <div class="flex items-center">
      <input v-model="form.agreeTerms" type="checkbox" id="agree-terms" required
             class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
      <label for="agree-terms" class="ml-2 block text-sm text-gray-900">
        I agree to the Terms and Conditions
      </label>
    </div>

    <input type="hidden" name="_csrf_token" :value="csrfToken">

    <button type="submit" 
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90">
      Register
    </button>
  </form>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import type { RegistrationData } from '@/types/auth'

export default defineComponent({
  name: 'RegisterForm',
  props: {
    csrfToken: {
      type: String,
      required: true
    }
  },
  emits: ['submit'],
  setup(props, { emit }) {
    const form = ref<RegistrationData>({
      email: '',
      password: '',
      _csrf_token: props.csrfToken,
      agreeTerms: false
    })

    const handleSubmit = () => {
      emit('submit', form.value)
    }

    return {
      form,
      handleSubmit
    }
  }
})
</script>