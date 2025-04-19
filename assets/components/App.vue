<template>
  <div class="min-h-screen bg-gradient-to-br from-primary to-secondary">
    <nav class="bg-white/10 backdrop-blur-lg border-b border-white/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="text-white font-bold text-xl">SecureZ</div>
          </div>
          <div class="flex items-center space-x-4">
            <a href="/api/doc" class="text-white hover:text-gray-200">API Docs</a>
            <a href="/logout" class="px-4 py-2 rounded-md bg-white/20 text-white hover:bg-white/30 transition-colors">
              Logout
            </a>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white rounded-lg shadow-xl p-8">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-3xl font-bold text-gray-900 mb-8">Welcome {{ username }}</h1>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Profile Information</h2>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Email</label>
                  <div class="mt-1 text-gray-900">{{ email }}</div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Roles</label>
                  <div class="mt-1 flex gap-2">
                    <span v-for="role in roles" :key="role" 
                          class="px-2 py-1 text-xs font-medium rounded-full"
                          :class="roleClasses(role)">
                      {{ role }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Security Status</h2>
              <div class="space-y-4">
                <div class="flex items-center">
                  <div class="w-4 h-4 rounded-full" :class="isVerified ? 'bg-green-500' : 'bg-red-500'"></div>
                  <span class="ml-2 text-sm font-medium text-gray-700">
                    {{ isVerified ? 'Email Verified' : 'Email Not Verified' }}
                  </span>
                </div>
                <div class="flex items-center">
                  <div class="w-4 h-4 rounded-full" :class="hasJWT ? 'bg-green-500' : 'bg-yellow-500'"></div>
                  <span class="ml-2 text-sm font-medium text-gray-700">
                    {{ hasJWT ? 'JWT Token Valid' : 'JWT Token Missing' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'App',
  data() {
    return {
      username: 'User',
      email: 'user@example.com',
      roles: ['ROLE_USER'],
      isVerified: true,
      hasJWT: false
    }
  },
  methods: {
    roleClasses(role: string): string {
      return role === 'ROLE_ADMIN' 
        ? 'bg-purple-100 text-purple-800'
        : 'bg-blue-100 text-blue-800'
    }
  },
  mounted() {
    // Here you would typically fetch user data from your API
    const jwt = localStorage.getItem('jwt_token')
    this.hasJWT = !!jwt
  }
})
</script>