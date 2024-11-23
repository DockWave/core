# Core Framework

Core Framework is a PHP-based microservice designed for high-performance and scalable web applications. It supports asynchronous operations using ReactPHP and provides modular implementations for handling various transfer methods (`curl`, `proxy`, and `queue`).

## Features

- **Asynchronous HTTP Server** powered by ReactPHP.
- **JWT Authentication** with RSA encryption.
- **Pluggable Transfer Methods**:
    - `curl`: HTTP requests using Guzzle.
    - `proxy`: Forwarding requests to another service.
    - `queue`: Placeholder for future queue-based processing.
- **Caching** using ReactPHP CacheInterface.
- **Dynamic Routing** configuration via YAML.
- **Logging** with Monolog.
- **Configuration Management** via Symfony YAML.

## Requirements

- PHP 8.2 or later
- Composer
- Docker (optional, for containerization)
- Kubernetes (optional, for deployment)

## Installation

### Clone the Repository
```bash
git clone https://github.com/your-username/core-framework.git
cd core-framework
```

### Update Configuration

Modify `conf.d/global.yaml` for your routing and caching preferences:

```yaml
server:
  port: 8080
  logger: 1  # Enable logging
  cache-limit-items: 100
```

## Usage

### Routes Configuration

Routes are defined in `conf.d/global.yaml`:

```yaml
routing:
  github:
    uri: /github
    method: proxy
    to: https://github.com
    cache:
      enable: 1
      ttl: 10
  auth:
    uri: /auth
    method: curl
    to: http://auth.service.local
```

## Make Command

- make install
- make uninstall
- make run
- make stop
- make generate_key
