#!/usr/bin/env bash
PATH=`dirname "$(readlink -f "$0")"`/tests/bin:$PATH
# create cache dir for soft-mocks
[ -d /tmp/mocks/ ] || mkdir /tmp/mocks/

if [ `./.travis.check-php-version-coverage.php` -eq "1" ]; then
  echo "xdebug.coverage_enable=On" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
fi
