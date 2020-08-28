# laravel-vue-zywebv3

use laravel5.5, vuejs, vuejs-route and fullcalendar

## Install
npm install  
composer update  
php artisan migrate:install  
php artisan migrate:refresh --seed  

## v3.2.2
[+]日程更新时，在航行记录中记录详细的更新内容.
[+]定金分类(现金/汇款/PayPay)
[+]定金收款日期
[+]定金金额大于0时，则定金分类及定金收款日期必须填写才可以创建或更新日程
[+]入金管理:对于定金的管理，仅对现金类型的定金作为未入金处理，对于汇款等其他类型的定金，直接作为已入金.
[+]入金管理:定金未入金的总金额统计