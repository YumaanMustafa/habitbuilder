const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const prisma = new PrismaClient();

exports.register = async (req, res) => {
    const { email, password, name } = req.body;
    try {
        const existingUser = await prisma.user.findUnique({ where: { email } });
        if (existingUser) return res.status(400).json({ error: 'User already exists' });

        const passwordHash = await bcrypt.hash(password, 10);
        const user = await prisma.user.create({
            data: { email, passwordHash, name },
        });

        const token = jwt.sign({ userId: user.id }, process.env.JWT_SECRET, { expiresIn: '7d' });
        res.json({ token, user: { id: user.id, email: user.email, name: user.name } });
    } catch (error) {
        res.status(500).json({ error: 'Registration failed' });
    }
};

exports.login = async (req, res) => {
    const { email, password } = req.body;
    try {
        const user = await prisma.user.findUnique({ where: { email } });
        if (!user) return res.status(400).json({ error: 'User not found' });

        const validPassword = await bcrypt.compare(password, user.passwordHash);
        if (!validPassword) return res.status(400).json({ error: 'Invalid password' });

        const token = jwt.sign({ userId: user.id }, process.env.JWT_SECRET, { expiresIn: '7d' });
        res.json({ token, user: { id: user.id, email: user.email, name: user.name } });
    } catch (error) {
        res.status(500).json({ error: 'Login failed' });
    }
};

exports.me = async (req, res) => {
    try {
        const user = await prisma.user.findUnique({ where: { id: req.user.userId } });
        if (!user) return res.sendStatus(404);
        res.json({ user: { id: user.id, email: user.email, name: user.name } });
    } catch (error) {
        res.sendStatus(500);
    }
};
