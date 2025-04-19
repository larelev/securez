import { ref } from 'vue'
import type { User } from '@/types/auth'

export const useAuthStore = () => {
    const user = ref<User | null>(null)
    const isAuthenticated = ref(false)

    const setUser = (userData: User | null) => {
        user.value = userData
        isAuthenticated.value = !!userData
    }

    return {
        user,
        isAuthenticated,
        setUser
    }
}