#!/bin/bash

docker build -t task-tracker .

# Start the task tracker application
docker run --rm -it task-tracker bash
