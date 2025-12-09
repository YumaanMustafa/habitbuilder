import clsx from 'clsx';
import { CheckCircle, Circle, Flame, Trophy } from 'lucide-react';
import { Button } from './Button';

export const HabitCard = ({ habit, onToggle }) => {
    return (
        <div className="bg-surface p-5 rounded-xl border border-gray-700 shadow-sm hover:border-gray-500 transition-colors">
            <div className="flex justify-between items-start mb-3">
                <div>
                    <h3 className="text-lg font-semibold text-text">{habit.title}</h3>
                    <span className={clsx(
                        "text-xs px-2 py-0.5 rounded-full uppercase font-bold tracking-wider",
                        habit.difficulty === 'HARD' ? "bg-red-500/10 text-red-400" :
                            habit.difficulty === 'MEDIUM' ? "bg-yellow-500/10 text-yellow-400" :
                                "bg-green-500/10 text-green-400"
                    )}>
                        {habit.difficulty}
                    </span>
                </div>
                <div className="flex items-center space-x-1 text-orange-400">
                    <Flame size={20} className={habit.currentStreak > 0 ? "fill-orange-400" : ""} />
                    <span className="font-bold">{habit.currentStreak}</span>
                </div>
            </div>

            <div className="bg-background/50 p-3 rounded-lg border border-gray-800 mb-4">
                <p className="text-sm text-gray-400 mb-1">Today's Micro-Task:</p>
                <p className="text-md text-white font-medium">{habit.microTask}</p>
            </div>

            <Button
                variant={habit.todayCompleted ? "secondary" : "primary"}
                className={clsx("w-full flex items-center justify-center space-x-2", habit.todayCompleted && "text-green-400 border-green-500/30")}
                onClick={() => onToggle(habit.id, !habit.todayCompleted)}
            >
                {habit.todayCompleted ? <CheckCircle size={20} /> : <Circle size={20} />}
                <span>{habit.todayCompleted ? "Completed" : "Mark as Done"}</span>
            </Button>
        </div>
    );
};
