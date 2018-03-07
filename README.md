<p align="center">
    <h1 align="center">PHP APi Scaffolding</h1>
    <br>
</p>

This project is an api framework in PHP, based on Yii2. You can take it as a api scaffolding.

The object for this project is to build api easily and funny.
##Environment Requirement
#####1. PHP Version > PHP5.5
#####2. Nginx or Apache
#####3. MySQL Version > MySQL 5.6

##Steps To Use
#####1. Clone the lastest source code
```
git clone git@github.com:xiaohei2015/php-api-scaffolding.git
```
#####2. Install reponsitory
```
php composer.phar install
```
#####3. Update database configuration under file 'environments/dev/common/config/main-local.php'
```
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=api_scaffolding_v1_0_0',
            'username' => 'root',
            'password' => '111111',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
```
#####4. Fire the database sql file under 'database/api_scaffolding_v1_0_0.sql'

#####5. Configure the Nginx/Apache, and restart Nginx/Apache. Below is the nginx configuration example
```
server{
	listen 1081;
	root /home/vm05/dongming/src/My/php-api-scaffolding/frontend/web;
	server_name 127.0.0.1;
	access_log /data/logs/nginx/api-api.scaffolding.com-access.log;
	error_log  /data/logs/nginx/api-api.scaffolding.com-error.log;


	location / {
		root   /home/vm05/dongming/src/My/php-api-scaffolding/frontend/web;
		index  index.html index.htm index.php;
		if (!-e $request_filename){
				rewrite ^/(.*) /index.php last;
		}
	}
	

	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		# NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

		# With php5-cgi alone:
		fastcgi_pass 127.0.0.1:9000;
		# With php5-fpm:
		#fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		include fastcgi_params;
	}
}

server{
	listen 1082;
	root /home/vm05/dongming/src/My/php-api-scaffolding/api/web;
	server_name 127.0.0.1;
	access_log /data/logs/nginx/api-api.scaffolding.com-access.log;
	error_log  /data/logs/nginx/api-api.scaffolding.com-error.log;


	location / {
		root   /home/vm05/dongming/src/My/php-api-scaffolding/api/web;
		index  index.html index.htm index.php;
		if (!-e $request_filename){
				rewrite ^/(.*) /index.php last;
		}
	}
	

	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		# NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

		# With php5-cgi alone:
		fastcgi_pass 127.0.0.1:9000;
		# With php5-fpm:
		#fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		include fastcgi_params;
	}
}

upstream scaffolding_gii{
	server 127.0.0.1:1081;
}
upstream scaffolding_api{
	server 127.0.0.1:1082;
}

server{
	listen 80;
	server_name api.scaffolding.com;
	access_log /data/logs/nginx/api.scaffolding.com-access.log;
	error_log  /data/logs/nginx/api.scaffolding.com-error.log;
	if ($host != 'api.scaffolding.com') {
		rewrite ^/(.*)$ http://api.scaffolding.com/$1 permanent;
	}

	location  ~ ^/gii\//* {
                proxy_pass      http://scaffolding_gii;
                proxy_set_header  X-Real-IP  $remote_addr;
                proxy_set_header   Host             $host;
                proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
        }
	location  ~ ^/* {
                proxy_pass      http://scaffolding_api;
                proxy_set_header  X-Real-IP  $remote_addr;
                proxy_set_header   Host             $host;
                proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
        }
}

```
#####6. Check whether you can visit URL 'http://api.scaffolding.com/api/v1/user/login'
If system response like '{"code":1,"msg":"page not found。","data":[],"type":"yii\\web\\NotFoundHttpException"}',
it means, your environment has been ready.

Tips: remember to configure your local hosts file, so that when you it can be direct to your local IP when visiting 'api.scaffolding.com'

##Features:
#####1. CRUD api can be easily built
a) Create models class by gii, e.g. common/models/Article.php<br/>
b) Create modelsBiz class by gii, e.g. common/modelsBiz/ArticleBiz.php<br/>
c) Create Controller class, e.g. api/modules/v1/controllers/Article2Controller.php, <br/>
d) Send request with URL 'http://api.scaffolding.com/v1/article2s', you will get a list api.<br/> 
e) You can also try below URL
 POST 'http://api.scaffolding.com/v1/article2s' with title=title1 and content=content1<br/>
 GET 'http://api.scaffolding.com/v1/article2s/1'<br/>
 PUT 'http://api.scaffolding.com/v1/article2s/1' with title=title11 and content=content11<br/>

#####2. Transaction handling
Refer to function createArticle under 'common/modelsBiz/ArticleBiz.php'.

#####3. Exception handling
Refer to function createArticle under 'common/modelsBiz/ArticleBiz.php'.

#####4. Params Validator
Refer to function actionAdd under 'api/modules/v1/controllers/ArticleController.php'.

You can also sponsor me to make this project better. Thanks!
![支付宝付款码](https://github.com/xiaohei2015/php-api-scaffolding/blob/master/alipay.png)
![微信付款码](https://github.com/xiaohei2015/php-api-scaffolding/blob/master/wechat.jpg)