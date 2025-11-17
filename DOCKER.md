# Docker Development Environment

This project uses Docker for a consistent development environment across different machines.

## Quick Start

### Using CI Mode (Default - PCOV for Coverage)

The default configuration uses PCOV for faster test coverage in CI environments:

```bash
# Build and start the container
docker compose build
docker compose up -d

# Run tests
make tests

# Generate coverage (uses PCOV)
make coverage
```

### Using Development Mode (Xdebug for Debugging)

For local development with Xdebug support:

1. **Create your local override file:**
   ```bash
   cp docker-compose.override.yml.dist docker-compose.override.yml
   ```

2. **Rebuild the container with dev target:**
   ```bash
   docker compose build
   docker compose up -d
   ```

3. **Verify Xdebug is installed:**
   ```bash
   docker compose run --rm php php -m | grep xdebug
   ```

## Xdebug Configuration

The development image includes these Xdebug settings:

```ini
xdebug.mode=coverage,debug
xdebug.start_with_request=trigger
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.idekey=PHPSTORM
```

### PHPStorm Setup

1. Go to `Settings > PHP > Debug`
2. Set Xdebug port to `9003`
3. Enable "Can accept external connections"
4. Start listening for PHP Debug Connections
5. Set breakpoints and run tests with `XDEBUG_TRIGGER=1`

### VS Code Setup

Add to `.vscode/launch.json`:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/app": "${workspaceFolder}"
            }
        }
    ]
}
```

### Trigger Xdebug

```bash
# Run tests with debugging
docker compose run --rm -e XDEBUG_TRIGGER=1 php vendor/bin/phpunit

# Or set environment variable in docker-compose.override.yml
```

## Environment Variables

### In `docker-compose.override.yml`:

```yaml
environment:
  # Xdebug mode (debug, coverage, profile, etc.)
  - XDEBUG_MODE=coverage,debug

  # Client host (where your IDE is running)
  - XDEBUG_CONFIG=client_host=host.docker.internal

  # For Linux, use your actual IP:
  # - XDEBUG_CONFIG=client_host=192.168.1.100
```

## Switching Between Modes

### Switch to Dev Mode:
```bash
# Create override file if not exists
cp docker-compose.override.yml.dist docker-compose.override.yml

# Rebuild with dev target
docker compose build

# Verify
docker compose run --rm php php -m | grep xdebug
```

### Switch to CI Mode:
```bash
# Remove override file
rm docker-compose.override.yml

# Rebuild with ci target (default)
docker compose build

# Verify
docker compose run --rm php php -m | grep pcov
```

## Performance Comparison

| Mode | Extension | Coverage Speed | Debugging | Best For |
|------|-----------|----------------|-----------|----------|
| CI   | PCOV      | ⚡ Fast        | ❌ No     | CI/CD, Quick tests |
| Dev  | Xdebug    | 🐌 Slower      | ✅ Yes    | Local development, Debugging |

## Troubleshooting

### Xdebug not connecting?

1. **Check Xdebug is installed:**
   ```bash
   docker compose run --rm php php -m | grep xdebug
   ```

2. **Check Xdebug configuration:**
   ```bash
   docker compose run --rm php php -i | grep xdebug
   ```

3. **For Linux users:**
   - Replace `host.docker.internal` with your machine's IP address in `docker-compose.override.yml`
   - Find your IP: `ip addr show docker0 | grep inet`

4. **Check firewall:**
   - Ensure port 9003 is not blocked
   - On macOS: System Preferences > Security & Privacy > Firewall

### Coverage not working?

1. **Check if PCOV/Xdebug is enabled:**
   ```bash
   docker compose run --rm php php -m | grep -E "(pcov|xdebug)"
   ```

2. **Rebuild container:**
   ```bash
   docker compose build --no-cache
   ```

## Commands Reference

```bash
# Build containers
make setup                  # Build and install dependencies

# Development
make php                    # Open shell in PHP container
make tests                  # Run tests
make phpstan                # Run static analysis
make cs-fixer               # Fix code style
make coverage               # Generate coverage report

# Docker commands
docker compose build        # Build images
docker compose up -d        # Start containers
docker compose down         # Stop containers
docker compose logs -f      # View logs
```
