@servers(['web' => 'root@165.227.249.129'])

@task('deploy_facebook_comments_admin')
cd /var/www/html/facebook-comments-admin
sudo  git pull origin main
sudo  composer install
yarn
php artisan migrate
chmod 777 -R storage/
@endtask
