
FROM zaherg/php-7.2-xdebug-alpine

CMD  sudo mkdir /etc/periodic/everymin
ADD  everymin/phpcron /etc/periodic/everymin/phpcron

CMD  sudo mkdir /temp
ADD  temp/ip.txt  /temp/ip.txt
ADD  ipnotify.php /temp/ipnotify.php

ADD  root /etc/crontabs
CMD  chmod a+x /etc/periodic/everymin/phpcron 
CMD  crond -f