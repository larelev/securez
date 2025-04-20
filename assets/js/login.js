class LoginForm {
    async login (homeUrl) {
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('username').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch('/api/login_check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                console.log({token: data.token ?? 'not provided' })
                if (response.ok) {
                    // Store the token
                    localStorage.setItem('jwt_token', data.token);

                    // Redirect to home page
                    window.location.href = homeUrl;
                } else {
                    // Show error message
                    const errorDiv = document.getElementById('errorMessage');
                    errorDiv.querySelector('p').textContent = data.message || 'Authentication failed';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                const errorDiv = document.getElementById('errorMessage');
                errorDiv.querySelector('p').textContent = 'An error occurred during authentication';
                errorDiv.classList.remove('hidden');
            }
        });
    }
}

export default function useLoginForm() {
    return new LoginForm();
}