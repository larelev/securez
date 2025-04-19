# SecureZ - Version Update Guide

## Current Versions
- PHP: `>=8.2`
- Symfony: `7.2.*`
- Doctrine ORM: `^3.3`
- Doctrine Bundle: `^2.14`
- PHPUnit: `^12.1`

## Update Steps

1. **Backup Your Project**
   ```bash
   cp -r . ../securez-backup
   ```

2. **Check Current Dependencies**
   ```bash
   docker compose exec php composer show
   ```

3. **Update Composer**
   ```bash
   docker compose exec php composer self-update
   ```

4. **Check for Outdated Packages**
   ```bash
   docker compose exec php composer outdated
   ```

5. **Update Dependencies**
   ```bash
   # Update all dependencies
   docker compose exec php composer update

   # Update specific package
   docker compose exec php composer update symfony/*
   ```

6. **Clear Cache**
   ```bash
   docker compose exec php php bin/console cache:clear
   ```

7. **Run Tests**
   ```bash
   docker compose exec php php bin/phpunit
   ```

## Version Constraints Explained

- `*`: Any version
- `7.2.*`: Any version in 7.2.x branch
- `^2.14`: Version 2.14 or higher, but less than 3.0
- `>=8.2`: Version 8.2 or higher
- `^3.3`: Version 3.3 or higher, but less than 4.0

## Key Dependencies

### Production
```json
{
    "require": {
        "php": ">=8.2",
        "doctrine/orm": "^3.3",
        "symfony/framework-bundle": "7.2.*",
        "symfony/security-bundle": "7.2.*"
    }
}
```

### Development
```json
{
    "require-dev": {
        "doctrine/doctrine-fixtures-bundle": "^4.1",
        "phpunit/phpunit": "^12.1",
        "symfony/maker-bundle": "^1.62"
    }
}
```

## Troubleshooting

1. If you encounter conflicts:
   ```bash
   docker compose exec php composer why-not package/name
   ```

2. For dependency issues:
   ```bash
   docker compose exec php composer depends package/name
   ```

3. To check security vulnerabilities:
   ```bash
   docker compose exec php composer audit
   ```

## Note
Always check the [Symfony Upgrade Guide](https://symfony.com/doc/current/setup/upgrade_major.html) before performing major version updates.