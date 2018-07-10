### Тест для Кораблика  
### Установка  
Склонировать репозиторий:  
```
git clone https://github.com/PeterZhukov/KorablikTest.git
```
&nbsp;  
Внимание: папка KorablikTest/web должна быть document root вашего веб сервера. (DocumentRoot в apache и root в nginx)  
```
cd KorablikTest
```
&nbsp;  
Установить зависимости композера:  
```
php composer.phar install
```
&nbsp;    
Если вы работаете от другого пользователя нежели веб-сервер, то выполните команду:  
```
chown -R user:user var
```
где `user` - имя пользователя от которого работает веб сервер (обычно httpd, apache).  
Узнать имя пользователя можно выполнив команду  
```
ps aux | grep httpd
```
вывод:  
```
apache   30224  0.0  0.2 973856 10808 ?        S    20:21   0:00 /usr/sbin/http  -DFOREGROUND
```
означает, что веб-сервер работает от пользователя ``apache``  
&nbsp;    
В файле app/config/config.yml установите следующие параметры:  
```
peter_zhukov_korablik_test:
    api_base_url: http://host_name.ru/base_url/
    api_reponse_format: json
```
внимание! в файле нельзя использовать табуляцию. Отступы должны быть обязательно и они должны быть выполнены пробелами. Число пробелов смотрите в файле (должно совпадать с другими отступами).  
, где
```api_base_url``` - имя хоста и базовый путь, откуда будут браться товары (в дистрибутиве есть данный контроллер, см. ниже)
```api_response_format``` - одно из значений: json, xml. Формат в котором API возвращает данные.  
&nbsp;    
Так же для тестов в ``app/config/parameters.yml`` необходимо установить
```tests_server_name: YourServerName``` - имя сервера для тестов (в дистрибутиве есть контроллер, который возвращает данные, и для него написаны тесты, необходимо указать адрес данного контроллера - см. ниже)  
&nbsp;    
Проверьте, что открываются следующие URL:  
http://host-name.ru/test_zhukov/products - api возвращающее товары в формате json  
http://host-name.ru/test_zhukov/products_xml - api возвращающее товары в формате xml  
http://host-name.ru/test_zhukov/products_error - api возвращающее валидную ошибку в формате json  
http://host-name.ru/test_zhukov/products_xml_error - api возвращающее валидную ошибку в формате xml  
&nbsp;    
&nbsp;    
Т.е. параметр   
```api_base_url``` должен быть http://host-name.ru/test_zhukov/  
а параметр  
```tests_server_name``` - должен быть ```host-name.ru```  
&nbsp;    
Запуск команды:  
из папки с дистрибутивом (в нашем случае KorablikTest)  
```
php bin/console zhukov:get-products
```
&nbsp;    
Запуск тестов:  
```
php vendor/bin/simple-phpunit  src/PeterZhukov/KorablikTestBundle/
```
&nbsp;    
Спасибо за внимание.
