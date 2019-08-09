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

@task('testing')
    cd {{ $base_dir }}
    docker-compose exec -T php bash

    {{-- get the current working directory name --}}
    LAST=`readlink -f current` 
    LAST=${LAST##*/}

    cd releases
    echo 'Removing old releases but one last for backup'
    ls | grep -v ${LAST} | xargs rm -rfv
@endtask

@task('rollback')
    cd {{ $base_dir }}
    docker-compose exec -T php bash

    {{-- get the current working directory name --}}
    CURRENT=`readlink -f current` 
    CURRENT=${CURRENT##*/}

    cd releases
    LAST=`ls | grep -v ${CURRENT} | sort`
    echo ${LAST}

    {{-- php artisan migrate:rollback --force --}}
    ln -nfs ${LAST} {{ $app_dir }}/current
@endtask

@task('try_migration')
    {{-- login to the docker --}}
    cd {{ $base_dir }}
    docker-compose exec -T php bash

    php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60
    php artisan config:cache
    php artisan route:cache
    php artisan cache:clear
    php artisan view:clear

    if php artisan migrate:check; then 
        {{-- no migration available --}}
        echo 'No migration available, performing only mapping update for elasticsearch'
        php artisan elastic:update-mapping "App\SongLyric"
        php artisan elastic:update-mapping "App\Author"
    else
        echo 'Migrations available, migrating database and elasticsearch'
        php artisan migrate --force

        NEW_UUID=$(cat /dev/urandom | tr -dc 'a-z0-9' | fold -w 4 | head -n 1)
        php artisan elastic:migrate "App\SongLyric" song_lyric_${NEW_UUID}
        php artisan elastic:migrate "App\Author" author_${NEW_UUID}
    fi
 
    php artisan up
@endtask

@task('clone_repository')
    {{-- login to the docker --}}
    cd {{ $base_dir }}
    docker-compose exec -T php bash

    {{-- get the current working directory name --}}
    LAST=`readlink -f current` 
    LAST=${LAST##*/}

    cd releases
    echo 'Removing old releases but one last for backup'
    ls | grep -v ${LAST} | xargs rm -rfv

    {{-- clone git repositiory into a new folder --}}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard master

    {{-- link node_modueles, storage, .env from the main directory--}}
    {{-- echo 'Linking node_modules directory'
    ln -nfs {{ $app_dir }}/node_modules {{ $new_release_dir }}/node_modules --}}
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage
    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $app_dir }}/.env.production


    {{-- run composer --}}
    echo "Starting deployment ({{ $release }})"
    composer install --optimize-autoloader --no-dev
    composer dump-auto

    {{-- run yarn --}}
    yarn install
    yarn run dev

    rm -rf node_modules

    php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60
    php artisan config:cache
    php artisan route:cache
    php artisan cache:clear
    php artisan view:clear

    if php artisan migrate:check; then 
        {{-- no migration available --}}
        echo 'No migration available, performing only mapping update for elasticsearch'
        php artisan elastic:update-mapping "App\SongLyric"
        php artisan elastic:update-mapping "App\Author"
    else
        echo 'Migrations available, migrating database and elasticsearch'
        php artisan migrate --force

        NEW_UUID=$(cat /dev/urandom | tr -dc 'a-z0-9' | fold -w 4 | head -n 1)
        php artisan elastic:migrate "App\SongLyric" song_lyric_${NEW_UUID}
        php artisan elastic:migrate "App\Author" author_${NEW_UUID}
    fi

    php artisan up

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

