# Memcached Tool

This is a simple command line tool allowing you to:

 * View basic stats about a Memcached server.
 * List keys on a Memcached server (including regex lookups.)
 * Delete keys on a Memcached server (including regex lookups.)

## Installation

To install globally on your system run:

```
composer global require joelvardy/memcachedtool
```

### Your Path

To make binaries installed with Composer available globally you must add `$COMPOSER_HOME/vendor/bin` to your path, for example:

```
echo "PATH=\$PATH:\$HOME/.composer/vendor/bin" >> ~/.bashrc
source ~/.bashrc
```

## Commands

The default Memcached host and port will be used unless specified, to specify them add `--host 129.168.0.48 --port 11322` to the commands.

### Stats

```
memcachedtool stats
```

### List Keys

```
# All keys
memcachedtool keys

# Keys matching expression
memcachedtool keys --regex "/^post_2_(.*)$/"
```

### Delete Keys

```
# All keys
memcachedtool delete --all

# Keys matching expression
memcachedtool delete --regex "/^post_2_(.*)$/"
```
