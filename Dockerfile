
FROM zaherg/php-7.2-xdebug-alpine
ADD  everymin /etc/periodic/everymin
ADD  temp  /temp
ADD  root /etc/crontabs
CMD  chmod a+x /etc/periodic/everymin/phpcron 
CMD  crond -f