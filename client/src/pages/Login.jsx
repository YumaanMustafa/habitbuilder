import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate, Link } from 'react-router-dom';
import { Input } from '../components/Input';
import { Button } from '../components/Button';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const { login } = useAuth();
    const navigate = useNavigate();
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await login(email, password);
            navigate('/');
        } catch (err) {
            setError('Invalid credentials');
        }
    };

    return (
        <div className="flex h-screen items-center justify-center bg-background px-4">
            <div className="w-full max-w-md bg-surface p-8 rounded-2xl shadow-xl border border-gray-700">
                <h2 className="text-3xl font-bold text-center mb-6 bg-gradient-to-r from-primary to-purple-400 bg-clip-text text-transparent">Welcome Back</h2>
                {error && <div className="bg-red-500/10 text-red-500 p-3 rounded-lg mb-4 text-center">{error}</div>}
                <form onSubmit={handleSubmit}>
                    <Input type="email" label="Email" value={email} onChange={(e) => setEmail(e.target.value)} required />
                    <Input type="password" label="Password" value={password} onChange={(e) => setPassword(e.target.value)} required />
                    <Button className="w-full mt-2" type="submit">Log In</Button>
                </form>
                <p className="mt-4 text-center text-muted">
                    Don't have an account? <Link to="/register" className="text-primary hover:underline">Sign up</Link>
                </p>
            </div>
        </div>
    );
};

export default Login;
