#!/usr/bin/env bash
find /var/www/html/var/tmp -mindepth 1 -mtime +7 -delete
