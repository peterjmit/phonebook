set :application, "phonebook"
set :domain, "#{application}.peterjmit.com"
server "#{domain}", :app, :web, :db, :primary => true

set :repository,  "git@github.com:peterjmit/phonebook.git"

set :deploy_via, :copy
set :copy_exclude, [".git", ".DS_Store", "web/config.rb", "web/sass"]
set :scm, :git
set :branch, "master"

set :deploy_to,   "/var/www/#{domain}"
set :use_sudo, false
set :keep_releases, 3

set :shared_files, ["config/parameters.php"]

set :composer_bin, false
set :php_bin, "php"
set :composer_options, "--no-scripts --verbose --prefer-dist --optimize-autoloader"

logger.level = Capistrano::Logger::IMPORTANT
# logger.level = Logger::MAX_LEVEL

def remote_file_exists?(full_path)
  'true' == capture("if [ -e #{full_path} ]; then echo 'true'; fi").strip
end

namespace :deploy do
  task :restart_fpm do
    capifony_pretty_print "--> Restarting nginx"

    run "sudo service php5-fpm restart"

    capifony_puts_ok
  end
end

# Copied from capifony
namespace :composer do
  desc "Gets composer and installs it"
  task :get, :roles => :app, :except => { :no_release => true } do
    if !remote_file_exists?("#{latest_release}/composer.phar")
      print "--> Downloading Composer"

      run "#{try_sudo} sh -c 'cd #{latest_release} && curl -s http://getcomposer.org/installer | #{php_bin}'"
    else
      print "--> Updating Composer"

      run "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} composer.phar self-update'"
    end
  end

  desc "Updates composer"

  desc "Runs composer to install vendors from composer.lock file"
  task :install, :roles => :app, :except => { :no_release => true } do
    if !composer_bin
      composer.get
      set :composer_bin, "#{php_bin} composer.phar"
    end

    print "--> Installing Composer dependencies"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{composer_bin} install #{composer_options}'"
  end

  desc "Runs composer to update vendors, and composer.lock file"
  task :update, :roles => :app, :except => { :no_release => true } do
    if !composer_bin
      composer.get
      set :composer_bin, "#{php_bin} composer.phar"
    end

    print "--> Updating Composer dependencies"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{composer_bin} update #{composer_options}'"
  end

  desc "Dumps an optimized autoloader"
  task :dump_autoload, :roles => :app, :except => { :no_release => true } do
    if !composer_bin
      composer.get
      set :composer_bin, "#{php_bin} composer.phar"
    end

    print "--> Dumping an optimized autoloader"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{composer_bin} dump-autoload --optimize'"
  end

  task :copy_vendors, :except => { :no_release => true } do
    print "--> Copying vendors from previous release"

    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
  end
end

# always copy vendors
["composer:install", "composer:update"].each do |action|
  before action do
    composer.copy_vendors
  end
end

after "deploy:finalize_update" do
  composer.update
end

after "deploy:finalize_update", "deploy:restart_fpm"

after "deploy", "deploy:cleanup"