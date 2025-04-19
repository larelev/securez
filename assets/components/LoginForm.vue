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

    <input type="hidden" name="_csrf_token" :value="csrfToken">

    <button type="submit" 
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90">
      Sign in
    </button>
  </form>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import type { LoginCredentials } from '@/types/auth'

export default defineComponent({
  name: 'LoginForm',
  props: {
    csrfToken: {
      type: String,
      required: true
    }
  },
  emits: ['submit'],
  setup(props, { emit }) {
    const form = ref<LoginCredentials>({
      email: '',
      password: '',
      _csrf_token: props.csrfToken
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