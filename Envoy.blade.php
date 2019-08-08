@servers(['web' => 'msery@176.97.241.234 -p 2222'])

@setup
    $repository = 'git@gitlab.com:mdojcar/proscholy.cz.git';
    $app_dir = '/var/www/html';
    $releases_dir = $app_dir.'/releases';
    $base_dir = '/var/zpevnik';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    update_yarn
    give_permissions
    update_symlinks
@endstory

@story('deploy_docker')
    clone_repository
@endstory

@task('clone_repository')
    {{-- login to the docker --}}
    cd {{ $base_dir }}
    docker-compose exec -T php bash

    {{-- clone git repositiory int oa new folder --}}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    echo $PWD
    git reset --hard master

    {{-- link node_modueles, storage, .env from the main directory--}}
    echo 'Linking node_modules directory'
    ln -nfs {{ $app_dir }}/node_modules {{ $new_release_dir }}/node_modules
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage
    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env


    {{-- run composer --}}
    echo "Starting deployment ({{ $release }})"
    composer install --optimize-autoloader --no-dev
    composer dump-auto

    {{-- run yarn --}}
    yarn install
    yarn run production

    php artisan config:cache
    php artisan route:cache
    php artisan cache:clear
    php artisan view:clear

    {{-- the end --}}
    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('give_permissions')
    echo "Giving permissions for apache to access the storage and vendor folders"
    chown -R www-data:www-data {{ $new_release_dir }}/storage {{ $new_release_dir }}/vendor
@endtask

{{-- @task('list', ['on' => 'web'])
    cd /var/www
    ls -l
@endtask --}}

