import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import CreateHabit from './pages/CreateHabit';
import Stats from './pages/Stats';

const ProtectedRoute = ({ children }) => {
    const { user } = useAuth();
    if (!user) return <Navigate to="/login" />;
    return children;
};

function App() {
    return (
        <AuthProvider>
            <Router>
                <div className="min-h-screen bg-background text-text">
                    <Routes>
                        <Route path="/login" element={<Login />} />
                        <Route path="/register" element={<Register />} />
                        <Route path="/" element={
                            <ProtectedRoute>
                                <Dashboard />
                            </ProtectedRoute>
                        } />
                        <Route path="/create" element={
                            <ProtectedRoute>
                                <CreateHabit />
                            </ProtectedRoute>
                        } />
                        <Route path="/stats" element={
                            <ProtectedRoute>
                                <Stats />
                            </ProtectedRoute>
                        } />
                    </Routes>
                </div>
            </Router>
        </AuthProvider>
    );
}

export default App;
