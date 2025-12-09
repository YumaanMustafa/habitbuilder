import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import { Input } from '../components/Input';
import { Button } from '../components/Button';
import { ArrowLeft, Wand2 } from 'lucide-react';

const CreateHabit = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        title: '',
        difficulty: 'EASY',
        goalDescription: ''
    });

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post('/habits', formData);
            navigate('/');
        } catch (err) {
            alert('Failed to create habit');
        }
    };

    return (
        <div className="max-w-2xl mx-auto p-6">
            <Button variant="secondary" onClick={() => navigate(-1)} className="mb-6 flex items-center gap-2">
                <ArrowLeft size={16} /> Back
            </Button>

            <div className="bg-surface p-8 rounded-2xl border border-gray-700">
                <h2 className="text-2xl font-bold mb-6 flex items-center gap-3">
                    <Wand2 className="text-primary" /> Create New Habit
                </h2>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <Input
                        label="What is your big goal?"
                        placeholder="e.g. Read 50 books this year"
                        value={formData.title}
                        onChange={e => setFormData({ ...formData, title: e.target.value })}
                        required
                    />

                    <div>
                        <label className="block text-sm font-medium text-muted mb-2">Detailed Description (for AI Coach)</label>
                        <textarea
                            className="w-full px-4 py-2 bg-background text-text border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition-colors h-32"
                            placeholder="I want to improve my knowledge..."
                            value={formData.goalDescription}
                            onChange={e => setFormData({ ...formData, goalDescription: e.target.value })}
                            required
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-muted mb-2">Difficulty Level</label>
                        <div className="grid grid-cols-3 gap-4">
                            {['EASY', 'MEDIUM', 'HARD'].map(level => (
                                <button
                                    key={level}
                                    type="button"
                                    onClick={() => setFormData({ ...formData, difficulty: level })}
                                    className={`py-3 rounded-lg border font-medium transition-all ${formData.difficulty === level
                                            ? 'bg-primary/20 border-primary text-primary'
                                            : 'bg-background border-gray-700 text-muted hover:bg-gray-800'
                                        }`}
                                >
                                    {level}
                                </button>
                            ))}
                        </div>
                        <p className="text-xs text-muted mt-2">
                            {formData.difficulty === 'EASY' ? '5 mins/day' : formData.difficulty === 'MEDIUM' ? '10 mins/day' : '15+ mins/day'}
                        </p>
                    </div>

                    <Button type="submit" className="w-full py-3 text-lg">Generate Micro-Task Plan</Button>
                </form>
            </div>
        </div>
    );
};

export default CreateHabit;
