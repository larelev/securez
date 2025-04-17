class ApiInterceptor {
    static async fetch(url, options = {}) {
        // Add JWT token to headers
        const token = localStorage.getItem('token');
        if (token) {
            options.headers = {
                ...options.headers,
                'Authorization': `Bearer ${token}`
            };
        }

        try {
            const response = await fetch(url, options);
            
            // Handle 401 Unauthorized
            if (response.status === 401) {
                const refreshed = await this.refreshToken();
                if (refreshed) {
                    // Retry original request with new token
                    return this.fetch(url, options);
                }
            }
            
            return response;
        } catch (error) {
            console.error('API error:', error);
            throw error;
        }
    }

    static async refreshToken() {
        const refresh_token = localStorage.getItem('refresh_token');
        if (!refresh_token) return false;

        try {
            const response = await fetch('/api/token/refresh', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    refresh_token: refresh_token
                })
            });

            if (response.ok) {
                const data = await response.json();
                localStorage.setItem('token', data.token);
                return true;
            }

            // If refresh failed, redirect to login
            window.location.href = '/login';
            return false;
        } catch (error) {
            console.error('Token refresh error:', error);
            window.location.href = '/login';
            return false;
        }
    }
}