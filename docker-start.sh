#!/bin/bash

# Default environment
APP_ENV=${APP_ENV:-"dev"}

# Array to store all environment variables
declare -A env_vars

# Function to load variables from a file
load_env_file() {
    local env_file=$1
    if [[ -f "$env_file" ]]; then
        echo "Loading environment file: $env_file"
        while IFS='=' read -r key value; do
            # Skip comments and empty lines
            [[ $key =~ ^#.*$ || -z $key ]] && continue
            # Remove any leading/trailing whitespace and quotes
            value=$(echo "$value" | sed -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' -e 's/^"\(.*\)"$/\1/' -e "s/^'\(.*\)'$/\1/")
            env_vars[$key]=$value
        done < "$env_file"
    fi
}

# Load environment files in order of precedence
load_env_file ".env"
load_env_file ".env.local"
load_env_file ".env.$APP_ENV"
load_env_file ".env.$APP_ENV.local"

# Build Docker environment arguments string
DOCKER_ENV_ARGS=""
for key in "${!env_vars[@]}"; do
    DOCKER_ENV_ARGS="$DOCKER_ENV_ARGS -e $key=${env_vars[$key]}"
done

# Start Docker container with environment variables
# Note: Replace YOUR_IMAGE_NAME and optional arguments as needed
echo "Starting Docker container with loaded environment variables..."
docker run $DOCKER_ENV_ARGS YOUR_IMAGE_NAME
