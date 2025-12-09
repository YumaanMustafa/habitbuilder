const express = require('express');
const cors = require('cors');
const { PrismaClient } = require('@prisma/client');
const dotenv = require('dotenv');
const authRoutes = require('./routes/auth');
const habitRoutes = require('./routes/habits');

dotenv.config();

const app = express();
const prisma = new PrismaClient();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

// Routes
app.use('/api/auth', authRoutes);
app.use('/api/habits', habitRoutes);

app.get('/', (req, res) => {
    res.send('Micro-Habit Builder API');
});

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
