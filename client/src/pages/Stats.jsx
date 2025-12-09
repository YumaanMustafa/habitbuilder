import { Button } from '../components/Button';
import { useNavigate } from 'react-router-dom';

const Stats = () => {
    const navigate = useNavigate();
    return (
        <div className="p-10 text-center">
            <h1 className="text-2xl mb-4">Stats Coming Soon</h1>
            <Button onClick={() => navigate('/')}>Back to Dashboard</Button>
        </div>
    );
};

export default Stats;
