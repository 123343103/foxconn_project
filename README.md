# nginx容器 + php容器 + mysql容器
1.三个容器在同一网络下，默认是network_default网络；
2.fastcgi_pass   php-fpm:9000;（php-fpm:php容器的服务名）
3.root   /usr/local/nginx/html;
  fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
  将宿主机的网站根目录，挂载到nginx容器和php容器，在nginx容器nginx通过/usr/local/nginx/html可以找到项目，在PHP容器php-fpm通过$document_root的值（/usr/local/nginx/html）也可以找到项目。
3.修改php配置文件www.conf，将listen = 127.0.0.1:9000 改为 listen = 9000；
注意：文件权限问题。
