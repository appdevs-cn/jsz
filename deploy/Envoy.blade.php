@servers(['web' => 'heyunlong@138.197.101.86'])

@task('deploy')
    cd /home/heyunlong/jsz
    git pull origin activity
@endtask

@task('rollback')
    cd /home/heyunlong/jsz
    git reset --hard HEAD~1
@endtask

@task('tail')
    cd /var/log/php-fpm/
    tail -f *
@endtask