@servers(['web' => 'root@159.89.44.102'])

@task('deploy_facebook_comments_admin')
cd /var/www/html/facebook-comments-admin
git checkout .
git reset --hard
sudo  git pull origin main
sudo  composer install
yarn
php artisan migrate
chmod 777 -R storage/
chmod 777 -R bootstrap/cache
@endtask
