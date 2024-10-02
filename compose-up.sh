#!/bin/bash

function get_env_files() {
    local env_files=(".env.local" ".env.$1" ".env.$1.local")
    local found_files=()

    for file in "${env_files[@]}"; do
        if [[ -f $file ]]; then
            found_files+=("$file")
        fi
    done

    echo "${found_files[@]}"
}

function compose_up() {
    local env=$1
    local env_files
    env_files=$(get_env_files "$env")

    local compose_files=()
    for file in $env_files; do
        compose_files+=("--env-file ./$file")
    done

    eval "docker compose ${compose_files[@]} up -d"
}

compose_up $1
