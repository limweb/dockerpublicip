
FROM zaherg/php-7.2-xdebug-alpine
WORKDIR /var/www/html
CMD  sudo mkdir /etc/periodic/everymin
ADD  everymin/phpcron /etc/periodic/everymin/phpcron
CMD  sudo chmod +x /etc/periodic/everymin/phpcron
CMD  sudo chmod 0777 /etc/periodic/everymin/phpcron
CMD  sudo mkdir /temp
ADD  temp/ip.txt  /temp/ip.txt
ADD  temp/ipnotify.php /temp/ipnotify.php
ADD  root /etc/crontabs
ENTRYPOINT [“bash”, “-c”, “chmod 777 /etc/periodic/everymin/phpcron”]
CMD  crond -f