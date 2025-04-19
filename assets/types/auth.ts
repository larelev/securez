export interface LoginCredentials {
    email: string;
    password: string;
    _csrf_token: string;
}

export interface RegistrationData extends LoginCredentials {
    agreeTerms: boolean;
}

export interface User {
    email: string;
    roles: string[];
    isVerified: boolean;
}