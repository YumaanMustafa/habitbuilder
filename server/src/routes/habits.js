const express = require('express');
const router = express.Router();
const habitController = require('../controllers/habitController');
const authenticateToken = require('../middleware/authMiddleware');

router.use(authenticateToken); // Protect all habit routes

router.post('/', habitController.createHabit);
router.get('/', habitController.getHabits);
router.post('/:id/log', habitController.logDailyConfig);

module.exports = router;
