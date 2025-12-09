const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

// Mock function to generate micro-task
// In a real app, this would use an LLM or more complex rules
function generateMicroTask(title, difficulty, goalDescription) {
    const duration = difficulty === 'HARD' ? '15 mins' : difficulty === 'MEDIUM' ? '10 mins' : '5 mins';
    return `Spend ${duration} working on: ${title}. Focus on: ${goalDescription.substring(0, 50)}...`;
}

exports.createHabit = async (req, res) => {
    const { title, difficulty, goalDescription } = req.body;
    try {
        const habit = await prisma.habit.create({
            data: {
                userId: req.user.userId,
                title,
                difficulty,
                goalDescription,
                // In this simple model, the daily task is dynamic or just the habit title itself broken down.
                // We'll store the "Goal" and generate the daily task on the fly or in the logs.
            },
        });
        res.json(habit);
    } catch (error) {
        res.status(500).json({ error: 'Failed to create habit' });
    }
};

exports.getHabits = async (req, res) => {
    try {
        const habits = await prisma.habit.findMany({
            where: { userId: req.user.userId },
            include: {
                logs: {
                    where: {
                        date: {
                            gte: new Date(new Date().setHours(0, 0, 0, 0)) // Filter for today (simplified)
                        }
                    }
                }
            }
        });

        // Augment with "Today's Micro Task"
        const result = habits.map(h => {
            const todayLog = h.logs[0];
            return {
                ...h,
                microTask: generateMicroTask(h.title, h.difficulty, h.goalDescription),
                todayCompleted: !!todayLog?.completed
            };
        });

        res.json(result);
    } catch (error) {
        res.status(500).json({ error: 'Failed to fetch habits' });
    }
};

exports.logDailyConfig = async (req, res) => {
    const { id } = req.params; // habitId
    const { completed, notes } = req.body;
    const date = new Date();
    date.setHours(0, 0, 0, 0);

    try {
        // Check if logic exists
        let log = await prisma.habitLog.findFirst({
            where: {
                habitId: id,
                date: date
            }
        });

        if (log) {
            log = await prisma.habitLog.update({
                where: { id: log.id },
                data: { completed, notes }
            });
        } else {
            log = await prisma.habitLog.create({
                data: {
                    habitId: id,
                    date: date,
                    completed,
                    notes
                }
            });
        }

        // Update streak
        // Simplified streak logic: if completed, increment streak.
        // Real logic would check if yesterday was completed.
        // For this hackathon scope, we just increment if completed is true and it wasn't before?
        // Let's just update the habit streak simplified.
        if (completed) {
            await prisma.habit.update({
                where: { id },
                data: { currentStreak: { increment: 1 } }
            });
        }

        res.json(log);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Failed to log progress' });
    }
};
