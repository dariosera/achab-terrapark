#!/bin/bash

# Start npm run dev in the background
cd pannello/
npm run dev &
NPM_PID=$!

# Start PHP server in the background
cd ../api-pannello
php -S localhost:8000 &
PHP_PID=$!

# Start npm run dev in the background
cd ../frontend
npm run dev &
NPM2_PID=$!

# Start PHP server in the background
cd ../api
php -S localhost:8001 &
PHP2_PID=$!


# Function to handle script termination
cleanup() {
    echo "Stopping processes..."
    kill $NPM_PID $PHP_PID $NPM2_PID $PHP2_PID
    wait $NPM_PID $PHP_PID $NPM2_PID $PHP2_PID
    echo "Processes stopped. Exiting."
    exit
}

# Trap SIGINT and SIGTERM signals to clean up background processes
trap cleanup SIGINT SIGTERM

# Wait for both background processes to finish
wait $NPM_PID $PHP_PID $NPM2_PID $PHP2_PID