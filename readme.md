# Memcached Tool

This is a simple command line tool allowing you to:

 * View basic stats about a Memcached server
 * List keys on a Memcached server (including by regex)
 * Delete keys on a Memcached server (including by regex)

## Building on CentOS

To build:

```
sudo yum -y install composer php php-pecl-memcached
composer install
```

## Commands

The default Memcached host and port will be used unless specified, to specify them add `--host 129.168.0.48 --port 11322` to the commands.

### Stats

```
./memcachedtool stats --host 10.22.1.236
```

### List Keys

```
# All keys
./memcachedtool keys --host 10.22.1.236

# Keys matching regex
./memcachedtool keys --host 10.22.1.236 --regex "/^post_2_(.*)$/"
```

### Delete Keys

```
# All keys
./memcachedtool delete --all

# Keys matching regex
./memcachedtool delete --regex "/^key_2_(.*)$/"

# Values matching regex
./memcachedtool delete --vregex "/^value_2_(.*)$/"
```
