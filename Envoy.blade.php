@servers(['web' => 'root@165.227.249.129'])

@task('deploy_facebook_comments_admin')
cd /var/www/html/facebook-comments-admin
sudo -u www-data git pull origin master
sudo -u www-data composer update
php artisan migrate
npm run prod
@endtask
