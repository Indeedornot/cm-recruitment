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

# Write environment variables to a temporary .env file
temp_env_file=".env.temp"
> "$temp_env_file"
for key in "${!env_vars[@]}"; do
    echo "$key=${env_vars[$key]}" >> "$temp_env_file"
done

echo "Starting Docker container with loaded environment variables..."
docker compose --env-file "$temp_env_file" up -d

# Clean up temporary .env file
rm "$temp_env_file"
