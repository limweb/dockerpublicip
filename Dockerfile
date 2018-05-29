
FROM zaherg/php-7.2-xdebug-alpine
ADD  phpcron /etc/periodic/everymin
ADD  ipnotify.php /temp
ADD  ip.txt /temp
ADD  root /etc/crontabs
CMD  chmod a+x /etc/periodic/everymin/phpcron 
CMD  crond -fbS  && tail -f 



