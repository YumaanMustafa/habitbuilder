import { useState, useEffect } from 'react';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';
import { Plus, Layout } from 'lucide-react';
import { HabitCard } from '../components/HabitCard';
import { Button } from '../components/Button';

const Dashboard = () => {
    const { user, logout } = useAuth();
    const [habits, setHabits] = useState([]);
    const [loading, setLoading] = useState(true);

    const fetchHabits = async () => {
        try {
            const res = await axios.get('/habits');
            setHabits(res.data);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchHabits();
    }, []);

    const handleToggleHabit = async (id, status) => {
        try {
            // Optimistic update
            setHabits(prev => prev.map(h => h.id === id ? { ...h, todayCompleted: status } : h));

            await axios.post(`/habits/${id}/log`, { completed: status });
            // Refetch to ensure streak consistency
            fetchHabits();
        } catch (err) {
            console.error("Failed to update habit", err);
            fetchHabits(); // Revert
        }
    };

    return (
        <div className="max-w-4xl mx-auto p-6">
            <header className="flex justify-between items-center mb-8">
                <div>
                    <h1 className="text-3xl font-bold">Hello, {user?.name} 👋</h1>
                    <p className="text-muted">Ready to conquer your micro-habits?</p>
                </div>
                <div className="flex gap-4">
                    <Link to="/create">
                        <Button className="flex items-center gap-2">
                            <Plus size={20} /> New Habit
                        </Button>
                    </Link>
                    <Button variant="secondary" onClick={logout}>Logout</Button>
                </div>
            </header>

            {loading ? (
                <div className="text-center text-muted py-10">Loading habits...</div>
            ) : habits.length === 0 ? (
                <div className="text-center py-20 border-2 border-dashed border-gray-700 rounded-xl">
                    <h3 className="text-xl font-semibold mb-2">No habits yet?</h3>
                    <p className="text-muted mb-6">Start small. Create your first micro-habit now.</p>
                    <Link to="/create">
                        <Button>Create Habit</Button>
                    </Link>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {habits.map(habit => (
                        <HabitCard key={habit.id} habit={habit} onToggle={handleToggleHabit} />
                    ))}
                </div>
            )}
        </div>
    );
};

export default Dashboard;
